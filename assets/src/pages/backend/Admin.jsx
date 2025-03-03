import React from "react";
import ReactDOM from "react-dom/client";
import RouterAdmin from "../../components/admin/RouterAdmin.jsx";

const container = document.getElementById("root");
if (container) {
  const root = ReactDOM.createRoot(container);
  root.render(<RouterAdmin/>);
}