import React from "react";

const MessageItem = ({ phone_number, name, time, message }) => {
  return (
    <article className="bg-neutral-100 border-b-2 py-2 border-neutral-400 hover:bg-neutral-200">
      <div className="header-article p-2 py-1 text-xs">
        <span className="w-20 inline-block font-semibold">{phone_number}</span>
        <span>({name})</span>
        <span className="float-right">{time}</span>
      </div>
      <div className="body-article p-2 py-1">
        <p className="text-sm">{message}</p>
      </div>
    </article>
  );
};

export default MessageItem;
