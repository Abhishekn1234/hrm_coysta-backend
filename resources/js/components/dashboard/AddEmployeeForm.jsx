import React, { useState,useEffect } from "react";
import { Form, Button, Row, Col, Tab, Nav, Card } from "react-bootstrap";
import { BsPersonLinesFill } from "react-icons/bs";
import { FaMoneyBill, FaUniversity, FaWallet ,FaUser, FaBriefcase, FaFileAlt} from "react-icons/fa";
import { BsBuildingAdd } from "react-icons/bs";
import { MdPayment } from "react-icons/md";
import { HiOutlineDocumentArrowDown } from "react-icons/hi2"; // For Allowances
import { MdWorkOutline } from "react-icons/md"; // For Current Employment
import { FaHistory } from "react-icons/fa"; // For Employment History
import { FaFilePdf,FaPlus, FaIdCard, FaFileContract, FaFileMedical, FaGraduationCap } from "react-icons/fa";
import { toast } from 'react-toastify';
import axios from "axios";
export default function AddEmployeeForm({onClose}) {
  
  const SectionHeader = ({ icon: Icon, text }) => (
  <div className="d-flex align-items-center mb-3">
    <span
      style={{
        backgroundColor: "#3b5998", // Or Bootstrap blue: "#0d6efd"
        color: "#fff",
        borderRadius: "50%",
        padding: "10px",
        display: "inline-flex",
        alignItems: "center",
        justifyContent: "center",
        width: "40px",
        height: "40px",
        marginRight: "10px",
      }}
    >
      <Icon size={20} />
    </span>
    <h5 className="m-0">{text}</h5>
  </div>
);
const iconStyle = {
  backgroundColor: "#3b5998",
  color: "#fff",
  borderRadius: "50%",
  padding: "8px",
  fontSize: "18px", // Increase size
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  width: "34px",
  height: "34px",
};


const fileUploadStyle = {
  border: "1px dashed #ccc",
  borderRadius: "10px",
  padding: "15px 20px",
  marginBottom: "12px",
  display: "flex",
  alignItems: "center",
  justifyContent: "space-between",
  backgroundColor: "#fff",
  transition: "background-color 0.3s ease",
};

const fileUploadHoverStyle = {
  backgroundColor: "#f8f9fa",
};

const iconLeftStyle = {
  display: "flex",
  alignItems: "center",
  gap: "12px",
};

const iconCircleStyle = {
  backgroundColor: "#3b5998",
  color: "white",
  borderRadius: "50%",
  padding: "10px",
  display: "flex",
  alignItems: "center",
  justifyContent: "center",
  width: "40px",
  height: "40px",
  fontSize: "18px",
};
const [positions, setPositions] = useState([
  {
    previousCompany: '',
    previousStartDate: '',
    previousEndDate: '',
    previousPosition: '',
    previousResponsibilities: ''
  }
]);
const handlePositionChange = (index, event) => {
  const { name, value } = event.target;
  const updatedPositions = [...positions];
  updatedPositions[index][name] = value;
  setPositions(updatedPositions);
};

const addPosition = () => {
  setPositions([
    ...positions,
    {
      previousCompany: '',
      previousStartDate: '',
      previousEndDate: '',
      previousPosition: '',
      previousResponsibilities: ''
    }
  ]);
};
const removePosition = (index) => {
  const updatedPositions = [...positions];
  updatedPositions.splice(index, 1); // remove one at the given index
  setPositions(updatedPositions);
};

// 👇 Helper component for each file field
function FileUploadField({ icon, label, sublabel, name, accept, onChange }) {
  return (
    <div
      className="file-upload-wrapper"
      style={fileUploadStyle}
      onMouseEnter={(e) => (e.currentTarget.style.backgroundColor = fileUploadHoverStyle.backgroundColor)}
      onMouseLeave={(e) => (e.currentTarget.style.backgroundColor = "#fff")}
    >
      <div style={iconLeftStyle}>
        <div style={iconCircleStyle}>{icon}</div>
        <div>
          <strong>{label}</strong><br />
          <small>{sublabel}</small>
        </div>
      </div>
      <div>
        <label
          htmlFor={name}
          style={{
            backgroundColor: "#fff",
            border: "1px solid #0d6efd",
            color: "#0d6efd",
            padding: "5px 15px",
            borderRadius: "5px",
            cursor: "pointer",
            fontSize: "14px",
          }}
        >
          Browse
        </label>
        <Form.Control
          type="file"
          id={name}
          name={name}
          accept={accept}
          onChange={onChange}
          style={{ display: "none" }}
        />
      </div>
    </div>
  );
}
const iconBoxStyle = {
  backgroundColor: "#f0f4ff",
  border: "1px dashed #d9e1f7",
  borderRadius: "8px",
  padding: "15px 20px",
  display: "flex",
  alignItems: "center",
  justifyContent: "space-between",
  marginBottom: "10px",
};

const handleCancel = () => {
  // Reset all form fields to empty
  setFormData({
    previousCompany: '',
    previousStartDate: '',
    previousEndDate: '',
    previousPosition: '',
    previousResponsibilities: '',
    // ... add other fields if needed
  });
  onClose();
};

  const [activeTab, setActiveTab] = useState("personal");
 const initialFormData = {
  firstName: "",
  lastName: "",
  email: "",
  phone: "",
  dob: "",
  gender: "",
  address: "",

  emergencyContactName: "",
  emergencyContactRelationship: "",
  emergencyContactPhone: "",
  emergencyContactEmail: "",

  position: "",
  department: "",
  employmentType: "",
  hireDate: "",
  reportingManager: "",
  workLocation: "",

  baseSalary: "",
  payFrequency: "",
  housingAllowance: "",
  transportAllowance: "",
  medicalAllowance: "",
  otherAllowances: "",
  bankName: "",
  accountNumber: "",
  routingNumber: "",
  paymentMethod: "",
  documentsAuthentic:false,
  positions: [
    {
      previousCompany: '',
      previousStartDate: '',
      previousEndDate: '',
      previousPosition: '',
      previousResponsibilities: ''
    }
  ]
};


const [formData, setFormData] = useState(initialFormData);


  const [departments, setDepartments] = useState([]);

  const [enumOptions, setEnumOptions] = useState({
  employmentType: [],
  gender: [],
  workLocation: [],
  payFrequency: [],
  reportingManager: []
});

useEffect(() => {
  fetch("/api/v1/enums/users")
    .then((res) => res.json())
    .then((data) => setEnumOptions(data))
    .catch((err) => console.error("Failed to fetch enums", err));
}, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleFileChange = (e) => {
    const { name, files } = e.target;
    setFormData((prev) => ({ ...prev, [name]: files[0] }));
  };
 const fetchData=async()=>{
  const response=await axios.get("/api/v1/employees");

 }
  const handleCheckboxChange = (e) => {
    const { name, checked } = e.target;
    setFormData((prev) => ({ ...prev, [name]: checked }));
  };

// const handleSubmit = async (e) => {
//   e.preventDefault();

//   const formDataToSend = new FormData();

//   // Basic personal info
//   formDataToSend.append('firstName', formData.firstName);
//   formDataToSend.append('lastName', formData.lastName);
//   formDataToSend.append('email', formData.email);
//   formDataToSend.append('phone', formData.phone);
//   formDataToSend.append('dob', formData.dob);
//   formDataToSend.append('gender', formData.gender);
//   formDataToSend.append('address', formData.address);

//   // Emergency contact
//   formDataToSend.append('emergencyContactName', formData.emergencyContactName);
//   formDataToSend.append('emergencyContactRelationship', formData.emergencyContactRelationship);
//   formDataToSend.append('emergencyContactPhone', formData.emergencyContactPhone);
//   formDataToSend.append('emergencyContactEmail', formData.emergencyContactEmail || '');

//   // Employment
//   formDataToSend.append('position', formData.position);
//   formDataToSend.append('department', formData.department);
//   formDataToSend.append('employmentType', formData.employmentType.toUpperCase().replace('-', '_'));
//   formDataToSend.append('hireDate', formData.hireDate);
//   formDataToSend.append('reportingManager', formData.reportingManager);
//   formDataToSend.append('workLocation', formData.workLocation);

//   // Salary
//   formDataToSend.append('baseSalary', formData.baseSalary);
//   formDataToSend.append('payFrequency', formData.payFrequency);
//  formDataToSend.append('housingAllowance', parseFloat(formData.housingAllowance) || 0);
// formDataToSend.append('otherAllowances', parseFloat(formData.otherAllowances) || 0);

  
//   formDataToSend.append('transportAllowance', formData.transportAllowance || 0);
//   formDataToSend.append('medicalAllowance', formData.medicalAllowance || 0);
//   formDataToSend.append('documentsAuthentic', formData.documentsAuthentic ? '1' : '0');

//   // Bank
//   formDataToSend.append('bankName', formData.bankName);
//   formDataToSend.append('accountNumber', formData.accountNumber);
//   formDataToSend.append('routingNumber', formData.routingNumber || '');
//   formDataToSend.append('paymentMethod', formData.paymentMethod || '');

//   // Documents
//   if (formData.resume) formDataToSend.append('resume', formData.resume);
//   if (formData.idProof) formDataToSend.append('idProof', formData.idProof);
//   if (formData.employmentContract) formDataToSend.append('employmentContract', formData.employmentContract);
//   if (formData.medicalCertificate) formDataToSend.append('medicalCertificate', formData.medicalCertificate);
//   if (formData.educationCertificates) formDataToSend.append('educationCertificates', formData.educationCertificates);

//   // Employment history
//   positions.forEach((position, index) => {
//     formDataToSend.append(`positions[${index}][previousCompany]`, position.previousCompany);
//     formDataToSend.append(`positions[${index}][previousStartDate]`, position.previousStartDate);
//     formDataToSend.append(`positions[${index}][previousEndDate]`, position.previousEndDate);
//     formDataToSend.append(`positions[${index}][previousPosition]`, position.previousPosition);
//     formDataToSend.append(`positions[${index}][previousResponsibilities]`, position.previousResponsibilities || '');
//   });

//   try {
//     const response = await axios.post("/api/hrm/personal-details", formDataToSend, {
//       headers: {
//         'Accept': 'application/json',
//         // Content-Type is automatically set by axios for FormData
//       },
//     });

//     if (!response.ok) {
//       const errorData = await response.json();
//       throw new Error(errorData.message || 'Submission failed');
//     }

//     const result = await response.json();
//     toast.success("Employee created successfully!");
//     onClose(); // Close the form if successful
    
//   } catch (error) {
//     console.error("Error:", error);
//     toast.error(error.message || "Network error. Please try again later.");
//   }
// };

const handleSubmit = async (e) => {
  e.preventDefault();

  // Validate required fields
  const requiredFields = [
    'firstName', 'lastName', 'email', 'phone', 'dob', 'gender', 'address',
    'emergencyContactName', 'emergencyContactRelationship', 'emergencyContactPhone',
    'position', 'department', 'employmentType', 'hireDate', 'reportingManager',
    'workLocation', 'baseSalary', 'payFrequency', 'bankName', 'accountNumber',
  ];
  const missingFields = requiredFields.filter(field => !formData[field]);
  if (missingFields.length > 0) {
    toast.error(`Please fill in: ${missingFields.join(', ')}`);
    return;
  }

  if (!formData.documentsAuthentic) {
    toast.error("Please confirm that all documents are authentic.");
    return;
  }

  const formDataToSend = new FormData();
  formDataToSend.append('firstName', formData.firstName || '');
  formDataToSend.append('lastName', formData.lastName || '');
  formDataToSend.append('email', formData.email || '');
  formDataToSend.append('phone', formData.phone || '');
  formDataToSend.append('dob', formData.dob || '');
  formDataToSend.append('gender', formData.gender || '');
  formDataToSend.append('address', formData.address || '');
  formDataToSend.append('place', formData.place || '');
  formDataToSend.append('qualification', formData.qualification || '');
  formDataToSend.append('experience', formData.experience || '');
  formDataToSend.append('expertise', formData.expertise || '');
  formDataToSend.append('hourlyRate', formData.hourlyRate || '');
  formDataToSend.append('monthlyRate', formData.monthlyRate || '');
  formDataToSend.append('annualCTC', formData.annualCTC || '');
  formDataToSend.append('probationPeriod', formData.probationPeriod || '');
  formDataToSend.append('joinType', formData.joinType || 'DIRECT');
  formDataToSend.append('image', formData.image || '');
  formDataToSend.append('emergencyContactName', formData.emergencyContactName || '');
  formDataToSend.append('emergencyContactRelationship', formData.emergencyContactRelationship || '');
  formDataToSend.append('emergencyContactPhone', formData.emergencyContactPhone || '');
  formDataToSend.append('emergencyContactEmail', formData.emergencyContactEmail || '');
  formDataToSend.append('position', formData.position || '');
  formDataToSend.append('department', formData.department || '');
  formDataToSend.append('employmentType', formData.employmentType ? formData.employmentType.toUpperCase().replace('-', '_') : '');
  formDataToSend.append('hireDate', formData.hireDate || '');
  formDataToSend.append('reportingManager', formData.reportingManager || '');
  formDataToSend.append('workLocation', formData.workLocation || '');
  formDataToSend.append('baseSalary', formData.baseSalary || '');
  formDataToSend.append('payFrequency', formData.payFrequency || '');
  formDataToSend.append('housingAllowance', parseFloat(formData.housingAllowance) || 0);
  formDataToSend.append('transportAllowance', parseFloat(formData.transportAllowance) || 0);
  formDataToSend.append('medicalAllowance', parseFloat(formData.medicalAllowance) || 0);
  formDataToSend.append('otherAllowances', parseFloat(formData.otherAllowances) || 0);
  formDataToSend.append('bankName', formData.bankName || '');
  formDataToSend.append('accountNumber', formData.accountNumber || '');
  formDataToSend.append('routingNumber', formData.routingNumber || '');
  formDataToSend.append('paymentMethod', formData.paymentMethod || '');
  formDataToSend.append('documentsAuthentic', formData.documentsAuthentic ? '1' : '0');

  if (formData.resume) formDataToSend.append('resume', formData.resume);
  if (formData.idProof) formDataToSend.append('idProof', formData.idProof);
  if (formData.employmentContract) formDataToSend.append('employmentContract', formData.employmentContract);
  if (formData.medicalCertificate) formDataToSend.append('medicalCertificate', formData.medicalCertificate);
  if (formData.educationCertificates) formDataToSend.append('educationCertificates', formData.educationCertificates);

  if (positions.length > 0) {
    positions.forEach((position, index) => {
      formDataToSend.append(`positions[${index}][previousCompany]`, position.previousCompany || '');
      formDataToSend.append(`positions[${index}][previousStartDate]`, position.previousStartDate || '');
      formDataToSend.append(`positions[${index}][previousEndDate]`, position.previousEndDate || '');
      formDataToSend.append(`positions[${index}][previousPosition]`, position.previousPosition || '');
      formDataToSend.append(`positions[${index}][previousResponsibilities]`, position.previousResponsibilities || '');
    });
  } else {
    formDataToSend.append('positions', '[]');
  }

  console.log('FormData:', [...formDataToSend.entries()].map(([key, value]) => `${key}: ${value instanceof File ? value.name : value}`));

  try {
    const response = await axios.post("/api/hrm/personal-details", formDataToSend, {
      headers: {
        'Accept': 'application/json',
      },
    });

    // Access response.data directly (axios parses JSON automatically)
    console.log('Response Data:', response.data);
    toast.success(response.data.message || "Employee created successfully!");
    onClose();
  } catch (error) {
    console.error("Error:", error);
    console.log("Response Data:", error.response?.data);
    let errorMessage = "Network error. Please try again later.";
    if (error.response?.status === 422) {
      const errors = error.response.data.errors || {};
      errorMessage = Object.values(errors).flat().join(', ');
    } else {
      errorMessage = error.response?.data?.message || errorMessage;
    }
    toast.error(errorMessage);
  }
};
const [position, setPosition] = useState([]);


useEffect(() => {
  axios.get("/api/v1/positions")
    .then(res => setPosition(res.data.positions || res.data)) // Use res.data, not res.json()
    .catch(err => console.error("Failed to load positions", err));
}, []);

useEffect(() => {
  const fetchDepartments = async () => {
    try {
      const response = await fetch("/api/v1/departments");
      const data = await response.json();

      if (response.ok) {
        setDepartments(data);
      } else {
        console.error("Failed to fetch departments:", data);
      }
    } catch (error) {
      console.error("Network error:", error);
    }
  };

  fetchDepartments();
}, []);


  const nextTab = () => {
    const tabs = ["personal", "employment", "salary", "documents"];
    const currentIndex = tabs.indexOf(activeTab);
    if (currentIndex < tabs.length - 1) {
      setActiveTab(tabs[currentIndex + 1]);
    }
  };

  const prevTab = () => {
    const tabs = ["personal", "employment", "salary", "documents"];
    const currentIndex = tabs.indexOf(activeTab);
    if (currentIndex > 0) {
      setActiveTab(tabs[currentIndex - 1]);
    }
  };

  return (
    <div className="p-4 border rounded">
    
      
      <Tab.Container activeKey={activeTab} onSelect={(k) => setActiveTab(k)}>
        <Nav variant="tabs" className="mb-4">
  <Nav.Item>
    <Nav.Link eventKey="personal" className="d-flex align-items-center gap-2">
      <FaUser style={iconStyle} />
      Personal
    </Nav.Link>
  </Nav.Item>
  <Nav.Item>
    <Nav.Link eventKey="employment" className="d-flex align-items-center gap-2">
      <FaBriefcase style={iconStyle} />
      Employment
    </Nav.Link>
  </Nav.Item>
  <Nav.Item>
    <Nav.Link eventKey="salary" className="d-flex align-items-center gap-2">
      <FaMoneyBill style={iconStyle} />
      Salary
    </Nav.Link>
  </Nav.Item>
  <Nav.Item>
    <Nav.Link eventKey="documents" className="d-flex align-items-center gap-2">
      <FaFileAlt style={iconStyle} />
      Documents
    </Nav.Link>
  </Nav.Item>
</Nav>

        
        <Tab.Content>
          {/* Personal Tab */}
         <Tab.Pane eventKey="personal">
  {/* Personal Info */}
  
      <Row className="mb-3">
        <Col md={6}>
          <Form.Group controlId="firstName">
            <Form.Label><strong>First Name</strong></Form.Label>
            <Form.Control type="text" placeholder="Enter first name" name="firstName" value={formData.firstName} onChange={handleChange} required />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group controlId="lastName">
            <Form.Label><strong>Last Name</strong></Form.Label>
            <Form.Control type="text" placeholder="Enter last name" name="lastName" value={formData.lastName} onChange={handleChange} required />
          </Form.Group>
        </Col>
      </Row>

      <Row className="mb-3">
        <Col md={6}>
          <Form.Group controlId="email">
            <Form.Label><strong>Email</strong></Form.Label>
            <Form.Control type="email" placeholder="Enter email" name="email" value={formData.email} onChange={handleChange} required />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group controlId="phone">
            <Form.Label><strong>Phone</strong></Form.Label>
            <Form.Control type="tel" placeholder="Enter phone" name="phone" value={formData.phone} onChange={handleChange} required />
          </Form.Group>
        </Col>
      </Row>

      <Row className="mb-3">
        <Col md={6}>
          <Form.Group controlId="dob">
            <Form.Label><strong>Date of Birth</strong></Form.Label>
            <Form.Control type="date" placeholder="dd-mm-yyyy" name="dob" value={formData.dob} onChange={handleChange} required />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group controlId="gender">
  <Form.Label>Gender</Form.Label>
  <Form.Control
    as="select"
    name="gender"
    value={formData.gender}
    onChange={handleChange}
    required
  >
    <option value="">-- Select Gender --</option>
    {enumOptions.gender.map((gender, index) => (
      <option key={index} value={gender}>{gender}</option>
    ))}
  </Form.Control>
</Form.Group>

        </Col>
      </Row>

      <Form.Group controlId="address" className="mb-3">
        <Form.Label><strong>Address</strong></Form.Label>
        <Form.Control as="textarea" rows={3} placeholder="Enter full address" name="address" value={formData.address} onChange={handleChange} required />
      </Form.Group>
    
  <hr/>
  {/* Emergency Contact */}
  
    <div className="d-flex align-items-center text-primary mb-3" style={{ fontSize: "1.2rem", fontWeight: "bold" }}>
  <BsPersonLinesFill className="me-2" />
  Emergency Contact
</div>

    
      <Row className="mb-3">
        <Col md={6}>
          <Form.Group controlId="emergencyContactName">
            <Form.Label><strong>Contact Name</strong></Form.Label>
            <Form.Control type="text" placeholder="Enter full name" name="emergencyContactName" value={formData.emergencyContactName} onChange={handleChange} required />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group controlId="emergencyContactRelationship">
            <Form.Label><strong>Relationship</strong></Form.Label>
            <Form.Control type="text" placeholder="Enter relationship" name="emergencyContactRelationship" value={formData.emergencyContactRelationship} onChange={handleChange} required />
          </Form.Group>
        </Col>
      </Row>

      <Row className="mb-3">
        <Col md={6}>
          <Form.Group controlId="emergencyContactPhone">
            <Form.Label><strong>Contact Phone</strong></Form.Label>
            <Form.Control type="tel" placeholder="Enter phone number" name="emergencyContactPhone" value={formData.emergencyContactPhone} onChange={handleChange} required />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group controlId="emergencyContactEmail">
            <Form.Label><strong>Contact Email</strong></Form.Label>
            <Form.Control type="email" placeholder="Enter email" name="emergencyContactEmail" value={formData.emergencyContactEmail} onChange={handleChange} />
          </Form.Group>
        </Col>
      </Row>
    
  
</Tab.Pane>


          
         <Tab.Pane eventKey="employment">
  {/* Current Employment Header */}
 

  <Row className="mb-3">
    <Col md={6}>
    <Form.Group controlId="position">
  <Form.Label><strong>Position</strong></Form.Label>
  <Form.Control
    as="select"
    name="position"
    value={formData.position}
    onChange={handleChange}
    required
  >
    <option value="">Select Position</option>
    {position.map((pos, idx) => (
      <option key={idx} value={pos}>{pos}</option>
    ))}
  </Form.Control>
</Form.Group>

    </Col>
    <Col md={6}>
     <Form.Group controlId="departmentSelect">
  <Form.Label>Select Department</Form.Label>
  <Form.Control
    as="select"
    name="department"
    value={formData.department}
    onChange={handleChange}
  >
    <option value="">Select Department</option>
    {departments.map((dept) => (
      <option key={dept.id} value={dept.id}>
        {dept.department_name}
      </option>
    ))}
  </Form.Control>
</Form.Group>


    </Col>
  </Row>

  <Row className="mb-3">
    <Col md={6}>
      <Form.Group controlId="employmentType">
  <Form.Label>Employment Type</Form.Label>
  <Form.Control
    as="select"
    name="employmentType"
    value={formData.employmentType}
    onChange={handleChange}
    required
  >
    <option value="">Select Employment Type</option>
    {enumOptions.employmentType?.map((type, index) => (
      <option key={index} value={type}>{type}</option>
    ))}
  </Form.Control>
</Form.Group>

    </Col>
    <Col md={6}>
      <Form.Group controlId="hireDate">
        <Form.Label><strong>Hire Date</strong></Form.Label>
        <Form.Control
          type="date"
          placeholder="dd-mm-yyyy"
          name="hireDate"
          value={formData.hireDate}
          onChange={handleChange}
          required
        />
      </Form.Group>
    </Col>
  </Row>

  <Row className="mb-3">
    <Col md={6}>
      <Form.Group controlId="reportingManager">
  <Form.Label>Reporting Manager</Form.Label>
  <Form.Control
    as="select"
    name="reportingManager"
    value={formData.reportingManager}
    onChange={handleChange}
    required
  >
    <option value="">Select Manager</option>
   {enumOptions.reportingManager?.map((manager, index) => (
  <option key={index} value={manager}>{manager}</option>
))}

  </Form.Control>
</Form.Group>

    </Col>
    <Col md={6}>
     <Form.Group controlId="workLocation">
  <Form.Label>Work Location</Form.Label>
  <Form.Control
    as="select"
    name="workLocation"
    value={formData.workLocation}
    onChange={handleChange}
    required
  >
    <option value="">Select Work Location</option>
    {enumOptions.workLocation?.map((location, index) => (
      <option key={index} value={location}>{location}</option>
    ))}
  </Form.Control>
</Form.Group>

    </Col>
  </Row>
   <hr/>
  {/* Employment History Header */}
  <SectionHeader icon={FaHistory} text="Employment History" />

  {positions.map((position, index) => (
  <div key={index}className="mb-4 border rounded p-3 position-relative">
     {positions.length > 1 && (
      <Button
        variant="outline-danger"
        size="sm"
        style={{ position: 'absolute', top: '10px', right: '10px' }}
        onClick={() => removePosition(index)}
      >
        Remove
      </Button>
    )}
    <Form.Group controlId={`previousCompany-${index}`} className="mb-4">
      <Form.Label className="fw-bold d-block mb-2" style={{ fontSize: '14px', color: '#495057' }}>Previous Company</Form.Label>
      <Form.Control
        type="text"
        placeholder="Enter company name"
        name="previousCompany"
        value={position.previousCompany}
        onChange={(e) => handlePositionChange(index, e)}
        style={{ backgroundColor: 'transparent', borderColor: '#adb5bd' }}
      />
    </Form.Group>

    <Row className="mb-4 g-3">
      <Col md={6}>
        <Form.Group controlId={`previousStartDate-${index}`}>
          <Form.Label className="fw-bold d-block mb-2" style={{ fontSize: '14px', color: '#495057' }}>Start Date</Form.Label>
          <Form.Control
            type="date"
            name="previousStartDate"
            value={position.previousStartDate}
            onChange={(e) => handlePositionChange(index, e)}
            style={{ backgroundColor: 'transparent', borderColor: '#adb5bd' }}
          />
        </Form.Group>
      </Col>
      <Col md={6}>
        <Form.Group controlId={`previousEndDate-${index}`}>
          <Form.Label className="fw-bold d-block mb-2" style={{ fontSize: '14px', color: '#495057' }}>End Date</Form.Label>
          <Form.Control
            type="date"
            name="previousEndDate"
            value={position.previousEndDate}
            onChange={(e) => handlePositionChange(index, e)}
            style={{ backgroundColor: 'transparent', borderColor: '#adb5bd' }}
          />
        </Form.Group>
      </Col>
    </Row>

    <Form.Group controlId={`previousPosition-${index}`} className="mb-4">
      <Form.Label className="fw-bold d-block mb-2" style={{ fontSize: '14px', color: '#495057' }}>Position</Form.Label>
      <Form.Control
        type="text"
        placeholder="Enter position"
        name="previousPosition"
        value={position.previousPosition}
        onChange={(e) => handlePositionChange(index, e)}
        style={{ backgroundColor: 'transparent', borderColor: '#adb5bd' }}
      />
    </Form.Group>

    <Form.Group controlId={`previousResponsibilities-${index}`} className="mb-4">
      <Form.Label className="fw-bold d-block mb-2" style={{ fontSize: '14px', color: '#495057' }}>Responsibilities</Form.Label>
      <Form.Control
        as="textarea"
        rows={3}
        placeholder="Describe responsibilities"
        name="previousResponsibilities"
        value={position.previousResponsibilities}
        onChange={(e) => handlePositionChange(index, e)}
        style={{ backgroundColor: 'transparent', borderColor: '#adb5bd', resize: 'none' }}
      />
    </Form.Group>
  </div>
))}

 <Button variant="outline-primary" className="d-flex align-items-center mt-2" onClick={addPosition}>
  <FaPlus className="me-2" />
  Add Another Position
</Button>

</Tab.Pane>


          
         <Tab.Pane eventKey="salary">
  
  
  
  <Row className="mb-3">
    <Col md={6}>
      <Form.Group controlId="baseSalary">
        <Form.Label><strong>Base Salary ($)</strong></Form.Label>
        <Form.Control
          type="number"
          placeholder="Enter amount"
          name="baseSalary"
          value={formData.baseSalary}
          onChange={handleChange}
          required
        />
      </Form.Group>
    </Col>
    <Col md={6}>
    <Form.Group controlId="payFrequency">
  <Form.Label>Pay Frequency</Form.Label>
  <Form.Control
    as="select"
    name="payFrequency"
    value={formData.payFrequency}
    onChange={handleChange}
    required
  >
    <option value="">Select Pay Frequency</option>
    {enumOptions.payFrequency?.map((freq, index) => (
      <option key={index} value={freq}>{freq}</option>
    ))}
  </Form.Control>
</Form.Group>

    </Col>
  </Row>

  {/* Allowances Section */}
  <hr />
  <SectionHeader icon={HiOutlineDocumentArrowDown} text="Allowances" />

  <Row className="mb-3">
    <Col md={6}>
      <Form.Group controlId="housingAllowance">
        <Form.Label><strong>Housing Allowance ($)</strong></Form.Label>
        <Form.Control
          type="number"
          placeholder="Enter amount"
          name="housingAllowance"
          value={formData.housingAllowance}
          onChange={handleChange}
        />
      </Form.Group>
    </Col>
    <Col md={6}>
      <Form.Group controlId="transportAllowance">
        <Form.Label><strong>Transport Allowance ($)</strong></Form.Label>
        <Form.Control
          type="number"
          placeholder="Enter amount"
          name="transportAllowance"
          value={formData.transportAllowance}
          onChange={handleChange}
        />
      </Form.Group>
    </Col>
  </Row>

  <Row className="mb-3">
    <Col md={6}>
      <Form.Group controlId="medicalAllowance">
        <Form.Label><strong>Medical Allowance ($)</strong></Form.Label>
        <Form.Control
          type="number"
          placeholder="Enter amount"
          name="medicalAllowance"
          value={formData.medicalAllowance}
          onChange={handleChange}
        />
      </Form.Group>
    </Col>
    <Col md={6}>
      <Form.Group controlId="otherAllowances">
        <Form.Label><strong>Other Allowances ($)</strong></Form.Label>
        <Form.Control
          type="number"
          placeholder="Enter amount"
          name="otherAllowances"
          value={formData.otherAllowances}
          onChange={handleChange}
        />
      </Form.Group>
    </Col>
  </Row>

  {/* Payment Information */}
  <hr />
  <SectionHeader icon={FaUniversity} text="Payment Information" />

  <Row className="mb-3">
    <Col md={6}>
      <Form.Group controlId="bankName">
        <Form.Label><strong>Bank Name</strong></Form.Label>
        <Form.Control
          type="text"
          placeholder="Enter bank name"
          name="bankName"
          value={formData.bankName}
          onChange={handleChange}
          required
        />
      </Form.Group>
    </Col>
    <Col md={6}>
      <Form.Group controlId="accountNumber">
        <Form.Label><strong>Account Number</strong></Form.Label>
        <Form.Control
          type="text"
          placeholder="Enter account number"
          name="accountNumber"
          value={formData.accountNumber}
          onChange={handleChange}
          required
        />
      </Form.Group>
    </Col>
  </Row>

  <Row className="mb-3">
    <Col md={6}>
      <Form.Group controlId="routingNumber">
        <Form.Label><strong>Routing Number</strong></Form.Label>
        <Form.Control
          type="text"
          placeholder="Enter routing number"
          name="routingNumber"
          value={formData.routingNumber}
          onChange={handleChange}
        />
      </Form.Group>
    </Col>
    <Col md={6}>
      <Form.Group controlId="paymentMethod">
        <Form.Label><strong>Payment Method</strong></Form.Label>
        <Form.Control
          type="text"
          placeholder="Enter payment method"
          name="paymentMethod"
          value={formData.paymentMethod}
          onChange={handleChange}
        />
      </Form.Group>
    </Col>
  </Row>
</Tab.Pane>


          <Tab.Pane eventKey="documents">
  
 

  <FileUploadField
    icon={<FaFilePdf />}
    label="Upload Resume"
    sublabel="PDF, DOC or DOCX (Max 5MB)"
    name="resume"
    accept=".pdf,.doc,.docx"
    onChange={handleFileChange}
  />

  <FileUploadField
    icon={<FaIdCard />}
    label="Upload ID Proof"
    sublabel="Passport, Driver's License or ID Card (JPG, PNG or PDF)"
    name="idProof"
    accept=".jpg,.jpeg,.png,.pdf"
    onChange={handleFileChange}
  />

  <FileUploadField
    icon={<FaFileContract />}
    label="Employment Contract"
    sublabel="PDF or DOCX (Max 10MB)"
    name="employmentContract"
    accept=".pdf,.docx"
    onChange={handleFileChange}
  />

  <FileUploadField
    icon={<FaFileMedical />}
    label="Medical Certificate"
    sublabel="JPG, PNG or PDF (Max 5MB)"
    name="medicalCertificate"
    accept=".jpg,.jpeg,.png,.pdf"
    onChange={handleFileChange}
  />

  <FileUploadField
    icon={<FaGraduationCap />}
    label="Education Certificates"
    sublabel="PDF, JPG or PNG (Max 10MB)"
    name="educationCertificates"
    accept=".pdf,.jpg,.jpeg,.png"
    onChange={handleFileChange}
  />

  {/* Confirmation Checkbox */}
  <Form.Group controlId="documentsAuthentic" className="mt-3">
    <Form.Check
      type="checkbox"
      label="I confirm that all documents provided are authentic and accurate"
      name="documentsAuthentic"
      checked={formData.documentsAuthentic}
      onChange={handleCheckboxChange}
      required
    />
  </Form.Group>
</Tab.Pane>

        </Tab.Content>
      </Tab.Container>
    <hr/>
     <div className="d-flex justify-content-between align-items-center mt-4">

  <Button variant="outline-danger" onClick={handleCancel}>
    Cancel
  </Button>

 
  <div>
    <Button variant="primary" onClick={nextTab}>
      Next
    </Button>
    <Button variant="success" onClick={handleSubmit} className="ms-2">
      Submit
    </Button>
  </div>
</div>

    </div>
  );
}