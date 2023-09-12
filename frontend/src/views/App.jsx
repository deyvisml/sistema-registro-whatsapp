import React from "react";
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
  const { register, handleSubmit } = useForm();
  const [errors, setErrors] = useState([]);

  const { data, error, isLoading } = useSWR("/messages", fetcher);

  const onSubmitForm = async (form_data) => {
    console.log("data", form_data);

    try {
      const { data } = await axiosClient.post("/send_message", form_data);
      console.log(data);
      //localStorage.setItem("AUTH_TOKEN", data.token);
      setErrors([]);

      if (!data.error_occurred) {
        toast(data.data);
      }
    } catch (error) {
      console.log(Object.values(error.response.data.errors));
      setErrors(Object.values(error.response.data.errors));
    }
  };

  return (
    <div className="bg-teal-400 w-full h-full flex flex-col justify-center items-center">
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
          {errors.length > 0 && (
            <p className="bg-red-500 text-white text-center text-sm w-full p-1 mb-3">
              {errors}
            </p>
          )}
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
                  d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              Contact
            </label>
            <select
              {...register("contact")}
              id="contact"
              className="border-2 p-2 px-1.5 w-full text-sm"
            >
              <option value="987654">987654 (Raul)</option>
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
              {...register("template")}
              id="contact"
              className="border-2 p-2 px-1.5 w-full text-sm"
            >
              <option value="message_template">Template 1 (Message)</option>
            </select>
          </div>
          <div className="pb-4">
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
                  d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                />
              </svg>
              Message
            </label>
            <textarea
              {...register("message")}
              id="message"
              className="border-2 p-2 px-1.5 w-full text-sm"
            ></textarea>
          </div>
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

          {isLoading ? (
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
        </section>
      </div>
    </div>
  );
};

export default App;
