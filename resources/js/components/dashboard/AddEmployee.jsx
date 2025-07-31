import React, { useState,useRef } from "react";
import { Container, Card, Modal, Button } from "react-bootstrap";
import { FiUserPlus, FiBriefcase } from "react-icons/fi";
import { FaUmbrellaBeach, FaMoneyBillWave } from "react-icons/fa";
import { HiOutlineDocumentArrowDown } from "react-icons/hi2";
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import AddEmployeeForm from "./AddEmployeeForm";
import AddDepartmentForm from "./AddDepartmentForm";
import ManageLeaves from "./ManageLeaves";
import ProcessPayroll from "./ProcessPayroll";
import Tables from "./Tables";
import {
  UserPlus, // Lucide-react icons or Bootstrap Icons
  Network,
  Umbrella,
  Wallet,
  FileDown
} from 'lucide-react'; 

export default function AddEmployee() {
  const [showModal, setShowModal] = useState(false);
  const [selectedId, setSelectedId] = useState(null);
  const ICON_SIZE = 32;
   const tableRef = useRef(); // 👈 Create ref

  const handleExport = () => {
    const data = tableRef.current?.getExportData();
    console.log(data);
    if (!data || data.length === 0) {
      alert("No data to export.");
      return;
    }

    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Employees");

    const excelBuffer = XLSX.write(workbook, { bookType: "xlsx", type: "array" });
    const blob = new Blob([excelBuffer], { type: "application/octet-stream" });
    saveAs(blob, "Employees.xlsx");
  };

 const handleCardClick = (id) => {
    if (id === 5) {
      handleExport();
    } else {
      setSelectedId(id);
      setShowModal(true);
    }
  };
  const handleClose = () => {
    setShowModal(false);
    setSelectedId(null);
  };

 const options = [
  { 
    id: 1, 
    title: "Add Employee",
    icon: <FiUserPlus  size={ICON_SIZE} />,
    bgColor: "#3b5998"
  },
  { 
    id: 2, 
    title: "Add Department", 
    icon: <FiBriefcase color="#3b5998" size={ICON_SIZE} />,
     bgColor: "blue"
  },
  { 
    id: 3, 
    title: "Manage Leaves", 
    icon: <FaUmbrellaBeach color="#3b5998" size={ICON_SIZE} />,
     bgColor: "blue"
  },
  { 
    id: 4, 
    title: "Process Payroll", 
    icon: <FaMoneyBillWave  size={ICON_SIZE} />,
     bgColor: "#28a745"
  },
  { 
    id: 5, 
    title: "Export Data", 
    icon: <HiOutlineDocumentArrowDown size={ICON_SIZE} />
  },
];


  const renderModalContent = () => {
    switch (selectedId) {
      case 1: return <AddEmployeeForm onClose={handleClose} />;
      case 2: return <AddDepartmentForm onClose={handleClose}/>;
      case 3: return <ManageLeaves  onClose={handleClose} />;
      case 4: return <ProcessPayroll onClose={handleClose}/>;
      // case 5: return <ExportData />;
      default: return null;
    }
  };

  return (
    <>
    <Container fluid className="mt-3 p-0 bg-light">
   <div
  className="d-flex justify-content-start gap-3 p-2 flex-nowrap"
  style={{
    overflowX: 'auto',
    scrollbarWidth: 'none',
    msOverflowStyle: 'none',
    paddingLeft: '10px', // add some horizontal space
    paddingRight: '10px'
  }}
>

      {options.map((item) => (
        <Card
          key={item.id}
          onClick={() => handleCardClick(item.id)}
          className="d-flex justify-content-center align-items-center rounded shadow-sm cursor-pointer me-3"
          style={{
            width: '235px',
            height: '80px',
            flexShrink: 0,
            backgroundColor: '#fff',
            transition: 'all 0.3s ease',
            border: '1px solid #e5e9f2',
            marginRight: '10px'
          }}
          role="button"
          tabIndex="0"
        >
          <div className="text-center">
            <div className="mb-1" style={{ color: '#3b5998' }}>{item.icon}</div>
            <div className="fw-bold text-dark" style={{ fontSize: '14px' }}>{item.title}</div>
          </div>
        </Card>
      ))}
    </div>
  </Container>

  {selectedId !== 5 && (
  <Modal show={showModal} onHide={handleClose} centered size="lg">
    <Modal.Header
      closeButton
      style={{
        backgroundColor: options.find(opt => opt.id === selectedId)?.bgColor || "#3b5998",
        color: "white"
      }}
    >
      <Modal.Title
        style={{
          color: "white",
          display: "flex",
          alignItems: "center",
          gap: "8px"
        }}
      >
        {selectedId === 1 && options.find(opt => opt.id === 1)?.icon}
        {options.find(opt => opt.id === selectedId)?.title || ""}
      </Modal.Title>
    </Modal.Header>
    <Modal.Body>
      {renderModalContent()}
    </Modal.Body>
  </Modal>
)}

<div style={{ display: "none" }}>
  <Tables ref={tableRef} />
</div>
    </>
  );
}