import React from "react";
import { HashRouter as Router, Routes, Route, useParams } from "react-router";
import { Table } from "./Table.jsx";
import Form from "./Form.jsx";
   
const RouterAdmin = () => {
  return (
    <Router> 
      <Routes>  
        <Route path="/" element={<Table/>}/> 
        <Route path="/create" element={<Form/>} /> 
        <Route path="/update/:id" element={<Form/>} />
      </Routes>
    </Router> 
  );
};

export default RouterAdmin;