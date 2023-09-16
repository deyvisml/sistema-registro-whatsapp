import React, { useEffect } from "react";
import { useForm } from "react-hook-form";
import axiosClient from "../config/axios";
import useSWR from "swr";
import moment from "moment";
import "moment/locale/es"; // without this line it didn't work
moment.locale("es");

import MessageItem from "../components/MessageItem";

import { useState } from "react";

import { ToastContainer, toast } from "react-toastify";

import "react-toastify/dist/ReactToastify.css";

const fetcher = (url) => {
  return axiosClient.get(url).then((result) => result.data);
};

const App = () => {
  const { register, handleSubmit, reset } = useForm();
  const [errors, setErrors] = useState([]);
  const { data, error, isLoading } = useSWR("/messages", fetcher);
  const [templates, setTemplates] = useState([]);
  const [fields, setFields] = useState([]);
  const [currentTemplateId, setCurrentTemplateId] = useState(0);

  useEffect(() => {
    // getting templates
    axiosClient
      .get("/templates")
      .then(({ data }) => {
        //console.log(data.data.templates);
        setTemplates(data.data.templates);
      })
      .catch((error) => console.error(error));
  }, []);

  useEffect(() => {
    // getting fields
    axiosClient
      .get("/fields", { params: { template_id: currentTemplateId } })
      .then(({ data }) => {
        console.log("fields: ", data.data.fields);
        setFields(data.data.fields);
      })
      .catch((error) => {
        console.error(error);
        setFields([]);
      });
  }, [currentTemplateId]);

  const onSubmitForm = async (form_data) => {
    console.log("data", form_data);

    try {
      const { data } = await axiosClient.post("/send_message", form_data);
      console.log(data);
      //localStorage.setItem("AUTH_TOKEN", data.token);
      setErrors([]);

      if (!data.error_occurred) {
        toast.success(data.data.message);
      } else {
        toast.error(data.data.message);
      }
    } catch (error) {
      console.log(Object.values(error.response.data?.errors));
      setErrors(Object.values(error.response.data?.errors));
    }

    reset();
  };

  const handleSelectTemplateChange = (e) => {
    const current_id = e.target.value;
    console.log(current_id);
    setCurrentTemplateId(Number(current_id));
  };

  return (
    <div className="bg-teal-400 w-full min-h-screen flex flex-col justify-center items-center">
      <ToastContainer />
      <div className="flex flex-wrap flex-col sm:flex-row items-center">
        <h2 className="text-center font-semibold text-3xl pb-4 text-white">
          WhatsApp API Application
        </h2>
        <img
          src="/images/whatsapp.png"
          alt=""
          className="w-12 sm:ml-2 sm:mb-4"
        />
      </div>
      <div className="w-full flex flex-wrap justify-center items-start">
        <form
          onSubmit={handleSubmit(onSubmitForm)}
          className="bg-white m-2 p-4 rounded-lg shadow-lg w-full sm:w-3/5 md:w-1/3"
        >
          <h3 className="text-center font-bold uppercase pb-2 text-gray-700">
            Send
          </h3>
          {errors.length > 0 &&
            errors.map((error, i) => {
              return (
                <p
                  key={i}
                  className="bg-red-500 text-white text-center text-sm w-full p-1 mb-1.5"
                >
                  {error}
                </p>
              );
            })}
          <div className="pb-4 mt-2">
            <label htmlFor="name" className="pb-2 flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                strokeWidth={1.5}
                stroke="currentColor"
                className="w-6 h-6 inline-block me-2"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              Phone number
            </label>
            <select
              {...register("phone_number")}
              id="phone_number"
              className="border-2 p-2 px-1.5 w-full text-sm"
            >
              <option value="51975032529">975032529 (Deyvis)</option>
              <option value="51950127962">950127962 (Raul)</option>
            </select>
          </div>
          <div className="pb-4">
            <label htmlFor="name" className="pb-2 flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                strokeWidth={1.5}
                stroke="currentColor"
                className="w-6 h-6 inline-block me-2"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"
                />
              </svg>
              Template
            </label>
            <select
              {...register("template_id")}
              id="phone_number"
              className="border-2 p-2 px-1.5 w-full text-sm"
              onChange={handleSelectTemplateChange}
            >
              <option value="">Selecciona una opción</option>
              {templates.length > 0 &&
                templates.map(({ id, name, unique_value }) => {
                  return (
                    <option key={id} value={id}>
                      {name}
                    </option>
                  );
                })}
            </select>
          </div>

          {fields.length > 0 &&
            fields.map(({ id, label, name, tag }) => {
              if (tag == "input") {
                return (
                  <div key={id} className="pb-4">
                    <label htmlFor={name} className="pb-2 flex items-center">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        strokeWidth={1.5}
                        stroke="currentColor"
                        className="w-6 h-6 inline-block me-2"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5"
                        />
                      </svg>

                      {label}
                    </label>
                    <input
                      {...register(name)}
                      id={name}
                      className="border-2 p-2 px-1.5 w-full text-sm"
                    />
                  </div>
                );
              } else if (tag == "textarea") {
                return (
                  <div key={id} className="pb-4">
                    <label htmlFor="message" className="pb-2 flex items-center">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        strokeWidth={1.5}
                        stroke="currentColor"
                        className="w-6 h-6 inline-block me-2"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5"
                        />
                      </svg>
                      {label}
                    </label>
                    <textarea
                      {...register(name)}
                      id={name}
                      className="border-2 p-2 px-1.5 w-full text-sm"
                    ></textarea>
                  </div>
                );
              }
            })}

          <div className="pb-2 pt-2">
            <input
              className="border-2 px-2 py-2 w-full bg-teal-600 hover:bg-teal-700 text-white font-bold uppercase rounded-md cursor-pointer"
              type="submit"
              value="Send Message"
            />
          </div>
        </form>

        <section className="bg-white m-2 p-4 rounded-lg shadow-lg w-full sm:w-3/5 md:w-1/3">
          <h3 className="text-center font-bold uppercase pb-2 text-gray-700">
            Received
          </h3>

          <div className="border h-96 overflow-y-scroll">
            {isLoading || error ? (
              <p>Cargando...</p>
            ) : (
              data.data.messages.map(
                ({ id, message, phone_number, received_at }) => (
                  <MessageItem
                    key={id}
                    phone_number={phone_number}
                    name="dyvs"
                    time={moment(received_at).calendar()}
                    message={message}
                  />
                )
              )
            )}
          </div>
        </section>
      </div>
    </div>
  );
};

export default App;
