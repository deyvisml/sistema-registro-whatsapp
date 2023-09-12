import React from "react";
import { useForm } from "react-hook-form";
import { Link } from "react-router-dom";
import axiosClient from "../config/axios";

const Register = () => {
  const { register, handleSubmit } = useForm();

  const onSubmitForm = async (form_data) => {
    console.log("data", form_data);

    try {
      const { data } = await axiosClient.post("/api/register", form_data);
      console.log(data);
      //localStorage.setItem("AUTH_TOKEN", data.token);
      //setErrores([]);
    } catch (error) {
      console.log(error);
      //setErrores(Object.values(error.response.data.errors));
    }
  };

  return (
    <div className="bg-teal-300 w-full h-full flex justify-center items-center">
      <form
        onSubmit={handleSubmit(onSubmitForm)}
        className="bg-white m-2 p-4 rounded-lg shadow-lg w-full sm:w-3/5 md:w-1/3"
      >
        <h2 className="text-center font-semibold text-2xl pb-4 text-teal-500">
          Register
        </h2>

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
            Name
          </label>
          <input
            className="border-2 px-2 py-1 w-full"
            type="text"
            {...register("name", { required: true })}
            id="name"
          />
        </div>
        <div className="pb-4">
          <label htmlFor="father_last_name" className="pb-2 flex items-center">
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
                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"
              />
            </svg>
            Father's last name
          </label>
          <input
            className="border-2 px-2 py-1 w-full"
            type="text"
            {...register("father_last_name")}
            id="father_last_name"
          />
        </div>
        <div className="pb-4">
          <label htmlFor="mother_last_name" className="pb-2 flex items-center">
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
                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"
              />
            </svg>
            Mother's last name
          </label>
          <input
            className="border-2 px-2 py-1 w-full"
            type="text"
            {...register("mother_last_name")}
            id="mother_last_name"
          />
        </div>
        <div className="pb-4">
          <label htmlFor="user" className="pb-2 flex items-center">
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
                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
              />
            </svg>
            WhatsApp number
          </label>
          <input
            className="border-2 px-2 py-1 w-full"
            type="text"
            {...register("user")}
            id="user"
          />
        </div>
        <div className="pb-4">
          <label htmlFor="password" className="pb-2 flex items-center">
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
                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
              />
            </svg>
            Password
          </label>
          <input
            className="border-2 px-2 py-1 w-full"
            type="password"
            {...register("password")}
            id="password"
          />
        </div>
        <div className="pb-2 pt-2">
          <input
            className="border-2 px-2 py-2 w-full bg-teal-600 hover:bg-teal-700 text-white font-bold uppercase rounded-md cursor-pointer"
            type="submit"
            value="Register"
          />
        </div>

        <div className="pb-2 pt-2 text-center hover:underline text-teal-700">
          <Link to="/login">Do you have an account?, Log in.</Link>
        </div>
      </form>
    </div>
  );
};

export default Register;
