import React, { useState, useEffect,useContext } from 'react';
import { useParams, useLocation, useNavigate } from 'react-router-dom';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import ViewPayslipModal from './ViewPayslipModal';
import {
  Tabs,
  Tab,
  Card,
  Row,
  Col,
  Modal,
  Form,
  Button,
  Accordion,
  useAccordionButton,
  AccordionContext
} from 'react-bootstrap';
import { toast } from 'react-toastify';
import './g.css';
import {
  FaEdit,
  FaTrash,
  FaUser,
  FaIdBadge,
  FaFileAlt,
  FaMoneyBillAlt,
  FaMoneyBillWave,
  FaUmbrellaBeach,
  FaCalendarCheck,
  FaFileInvoice,
  FaUsers,
  FaUserFriends,
  FaUserTie,
  FaPhone,
  FaDollarSign,
  FaCertificate,
  FaBuilding,
  FaTasks,
  FaStore,
  FaDownload,
  FaArrowLeft,
  FaPlane
} from 'react-icons/fa';

import { BsChevronRight, BsChevronDown } from 'react-icons/bs';
import axios from 'axios';
// Custom components
import Dashboard from './Dashboard';
import StaffTable from './StaffTable';
import CustomerCards from './CustomerCards';
import CustomerTable from './CustomerTable';
import VendorCards from './VendorCards';
import VendorTable from './VendorTable';
import PeopleTabs from './PeopleTabs';
import StaffDetails from './StaffDetails';
import ContactForm from './ContactForm';
import FinancialHR from './FinancialHr';
import Skills from './Skills';
import CustomerProfileForm from './CustomerProfileForm';

function CustomAccordionToggle({ children, eventKey, icon }) {
  const { activeEventKey } = useContext(AccordionContext);
  const decoratedOnClick = useAccordionButton(eventKey);

  // Check if eventKey is currently active (for alwaysOpen mode)
  const isOpen = Array.isArray(activeEventKey)
    ? activeEventKey.includes(eventKey)
    : activeEventKey === eventKey;
 
  return (
    <div
      onClick={decoratedOnClick}
      className="d-flex justify-content-between align-items-center p-3 mb-2"
      style={{
        backgroundColor: "#f8f9fc",
        borderRadius: "12px",
        cursor: "pointer",
        fontWeight: 500,
        transition: "background 0.2s",
      }}
    >
      <div className="d-flex align-items-center text-dark">
        <span className="me-2 text-primary">{icon}</span>
        {children}
      </div>

      {/* Show ↓ when open, → when closed */}
      {isOpen ? (
        <BsChevronDown className="accordion-chevron" />
      ) : (
        <BsChevronRight className="accordion-chevron" />
      )}
    </div>
  );
}
export default function Staffdetail() {
 const [loginEnabled, setLoginEnabled] = useState(false);
const [organization, setOrganization] = useState('');
const [username, setUsername] = useState('');
const [password, setPassword] = useState('');

  const [contactPersons, setContactPersons] = useState([]);
  const [addresses, setAddresses] = useState([]);
  const [documents, setDocuments] = useState([]);
  const [gstDetails, setGstDetails] = useState([]);
  const [showForm, setShowForm] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [tabKey, setTabKey] = useState('general');
  const [peopleTabKey, setPeopleTabKey] = useState(null);
  const [activeKey, setActiveKey] = useState('0');
  const [showAttendanceModal, setShowAttendanceModal] = useState(false);
const [attendanceStatus, setAttendanceStatus] = useState('');
const [attendanceHistory, setAttendanceHistory] = useState([]);
const [loadingHistory, setLoadingHistory] = useState(false);
  const [staff, setStaff] = useState(null);
  const [loading, setLoading] = useState(true);
  const [showLeaveModal, setShowLeaveModal] = useState(false);

const [leaveForm, setLeaveForm] = useState({
  employee_id: '', // filled from staff.id
  leave_type: '',
  from_date: '',
  to_date: '',
  reason: '',
  is_emergency: false,
  status: 'PENDING',
});

  const [newAddress, setNewAddress] = useState({
    address: '',
    city: '',
    state: '',
    pincode: ''
  });

  const { id } = useParams();
  const { state } = useLocation();
  const navigate = useNavigate();

 const [tasks, setTasks] = useState([]);
const [leaves, setLeaves] = useState([]);

useEffect(() => {
  if (id) {
    axios
      .get(`http://127.0.0.1:8000/api/v1/leave/leave/${id}`)
      .then((res) => {
        setLeaves([res.data]); // wrap in array to match list usage
      })
      .catch((err) => console.error('Failed to fetch leaves:', err));
  }
}, [id]);

const handleLeaveSubmit = async () => {
  try {
    const payload = {
      ...leaveForm,
      employee_id: staff?.id || id, // Ensure it's filled
      is_emergency: leaveForm.is_emergency ? 1 : 0,
      status: 'PENDING',
    };

    const res = await axios.post('http://127.0.0.1:8000/api/v1/leave/apply', payload);

    toast.success("✅ Leave requested successfully");
    setShowLeaveModal(false);
    setLeaveForm({
      employee_id: '',
      leave_type: '',
      from_date: '',
      to_date: '',
      reason: '',
      is_emergency: false,
      status: 'PENDING',
    });
  } catch (err) {
    console.error(err);
    toast.error("❌ Failed to submit leave request");
  }
};

 useEffect(() => {
    const fetchTasks = async () => {
      try {
        const res = await axios.get(`http://127.0.0.1:8000/api/v1/customers/${id}/tasks`);
        setTasks(res.data.tasks || []);
      } catch (error) {
        console.error("Error fetching tasks:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchTasks();
  }, [id]);
  const handleAddContact = () => {
    setContactPersons([
      ...contactPersons,
      {
        name: '',
        designation: '',
        workEmail: '',
        workPhone: '',
        personalEmail: '',
        personalPhone: '',
      },
    ]);
  };
  const userTasks = tasks.filter(task => String(task.user_id) === String(id));
  const handleAddClicks = () => {
    setShowForm(true);
  };

  const handleAddGst = () => {
    setGstDetails([...gstDetails, { gstNo: '', placeOfSupply: '' }]);
  };

  const handleRemoveGst = (index) => {
    const newGstDetails = [...gstDetails];
    newGstDetails.splice(index, 1);
    setGstDetails(newGstDetails);
  };

  const handleGstInputChange = (index, field, value) => {
    const newGstDetails = [...gstDetails];
    newGstDetails[index][field] = value;
    setGstDetails(newGstDetails);
  };
  const [showModal, setShowModal] = useState(false);
  const [modalAction, setModalAction] = useState('');
  const [formData, setFormData] = useState({
    user_id: '',
    task_name: '',
    task_description: '',
    deadline: '',
    project_value: '',
    project_status: '',
  });

 const handleClick = (label) => {
  setModalAction(label);

  // Reset form data to default empty strings before opening the modal
  setFormData({
    user_id: staff?.id || '',  // auto-assign if needed
    task_name: '',
    task_description: '',
    deadline: '',
    project_value: '',
    project_status: '',
  });

  setShowModal(true);
};


  const handleChanges = (e) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

const handleSubmit = async () => {
  try {
    if (!id) {
      toast.error("❌ Cannot find user ID.");
      return;
    }

    const payload = {
      ...formData,
      user_id: id, // ✅ Ensure user_id is set
    };

    const response = await axios.post(
      `http://127.0.0.1:8000/api/v1/customers/${id}/assign-task`,
      payload
    );

    toast.success("✅ Task assigned successfully");

    // ✅ Reset the form fields
    setFormData({
      user_id: '',
      task_name: '',
      task_description: '',
      deadline: '',
      project_value: '',
      project_status: '',
    });

    setShowModal(false);
  } catch (error) {
    console.error(error);
    toast.error("❌ Failed to assign task");
  }
};

const getStatusColor = (status) => {
  switch (status) {
    case "Present": return "success";
    case "Absent": return "danger";
    case "Late": return "warning";
    default: return "secondary";
  }
};
const handleSubmitAttendance = async () => {
  if (!attendanceStatus) return toast.error("Please select an attendance status");

  try {
    await axios.post(`http://127.0.0.1:8000/api/v1/users/${id}/attendance`, { status: attendanceStatus.toLowerCase() });
    toast.success("✅ Attendance marked!");
    setShowAttendanceModal(false);
  } catch (err) {
    console.error(err);
    toast.error("❌ Failed to mark attendance");
  }
};



  const generateDocuments = (staffData) => {
    const docs = [
      { name: 'CV', uploaded: !!staffData.resume, link: staffData.resume || '#' },
      { name: 'Photo', uploaded: !!staffData.photo, link: staffData.photo || '#' },
      { name: 'Passport Photo', uploaded: !!staffData.passport_size_photo, link: staffData.passport_size_photo || '#' },
      { name: 'Aadhar (Front)', uploaded: !!staffData.aadhar_front, link: staffData.aadhar_front || '#' },
      { name: 'Aadhar (Back)', uploaded: !!staffData.aadhar_back, link: staffData.aadhar_back || '#' },
      { name: 'PAN Card', uploaded: !!staffData.pan_card, link: staffData.pan_card || '#' },
      { name: 'Driving License (F)', uploaded: !!staffData.driving_license_front, link: staffData.driving_license_front || '#' },
      { name: 'Driving License (B)', uploaded: !!staffData.driving_license_back, link: staffData.driving_license_back || '#' },
      { name: 'Passport (F)', uploaded: !!staffData.passport_front, link: staffData.passport_front || '#' },
      { name: 'Passport (B)', uploaded: !!staffData.passport_back, link: staffData.passport_back || '#' },
      { name: 'Employee Contract', uploaded: !!staffData.employment_contract, link: staffData.employment_contract || '#' },
      { name: 'ESI Document', uploaded: !!staffData.esi_document, link: staffData.esi_document || '#' },
      { name: 'PF Document', uploaded: !!staffData.pf_document, link: staffData.pf_document || '#' },
    ];
    setDocuments(docs);
  };
  const handleChange = (e) => {
    const { name, value } = e.target;
    setStaff((prev) => ({ ...prev, [name]: value }));
  };

  const handleFileChange = (e) => {
  const file = e.target.files[0];
  setStaff((prev) => ({ ...prev, bank_document_file: file.name, bank_document: file }));
};


const [selectedSkills, setSelectedSkills] = useState([]);
const [showPayslipModal, setShowPayslipModal] = useState(false);

// On icon click
const handlePayslipView = () => setShowPayslipModal(true);
const populateLoginFields = (staffData) => {
  setLoginEnabled(!!staffData?.isLogin);
  setOrganization(staffData?.organization || '');
  setUsername(staffData?.email || ''); // assuming username = email
  setPassword(''); // keep blank for security (you usually don't fetch password)
};

 useEffect(() => {
  if (state?.staff) {
    setStaff(state.staff);
    populateLoginFields(state.staff);
    generateDocuments(state.staff);
    setLoading(false);
  } else {
    const fetchStaff = async () => {
      try {
        const res = await axios.get(`http://127.0.0.1:8000/api/v1/staff/${id}`);
        setStaff(res.data);
         
        populateLoginFields(res.data);
        generateDocuments(res.data);
      } catch (error) {
        console.error('Failed to fetch staff:', error);
      } finally {
        setLoading(false);
      }
    };
    fetchStaff();
  }
}, [id, state]);
const getCurrentMonthYear = () => {
  const now = new Date();
  return now.toLocaleString('default', { month: 'long', year: 'numeric' }); // e.g. "July 2025"
};
const currentMonthPayslip = staff && {
  month: getCurrentMonthYear(),
  gross_salary: staff.salary || 0,
  deductions: parseFloat(staff.daily_remuneration || 0),
  net_salary: staff.net_salary || 0,
};


  useEffect(() => {
    if (peopleTabKey === 'staff') {
      navigate('/admin/people');
    } else if (peopleTabKey === 'customers') {
      navigate('/customer');
    } else if (peopleTabKey === 'vendors') {
      navigate('/vendor');
    }
  }, [peopleTabKey, navigate]);
const handleUpdateStaff = async () => {
  try {
    const response = await axios.put(
      `http://127.0.0.1:8000/api/v1/staff/${staff.id}`,
      staff
    );
    toast.success("✅ Staff updated successfully!");
    setShowEditModal(false);
  } catch (error) {
    console.error("Failed to update staff:", error.response?.data || error.message);
    toast.error("❌ Failed to update staff. Please check your input.");
  }
};

  // Loading or error state UI
  if (loading) return <p>Loading...</p>;
  if (!staff) return <p className="text-danger">Staff not found</p>;
  if (!documents.length) return <div>Loading documents...</div>;
 const handleDownloadPayslip = (slip) => {
  console.log(`Downloading payslip for ${slip.month}`);

  // Ensure `staff` object is available in this scope
  if (!staff) {
    console.error('Staff data not available');
    return;
  }

  // Prepare data for XLSX
  const data = [
    { Field: 'Employee Name', Value: staff.name },
    { Field: 'Month', Value: slip.month },
    { Field: 'Amount', Value: staff.daily_renumeration || staff.gross_salary ||0 },
    { Field: 'Department', Value: staff.department || staff.designation },
    { Field: 'Date Generated', Value: new Date().toLocaleDateString() }
  ];

  // Convert to worksheet and workbook
  const worksheet = XLSX.utils.json_to_sheet(data);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Payslip');

  // Create blob and download
  const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  const blob = new Blob([excelBuffer], { type: 'application/octet-stream' });
  const fileName = `Payslip_${slip.month}_${staff.name.replace(/\s+/g, '_')}.xlsx`;
  saveAs(blob, fileName);
};

const handleDeleteStaff = async () => {
  if (!staff?.id) {
    toast.error("Invalid staff ID");
    return;
  }

  const confirmDelete = window.confirm("Are you sure you want to delete this staff?");
  if (!confirmDelete) return;

  try {
    await axios.delete(`http://127.0.0.1:8000/api/v1/staff/${staff.id}`);
    toast.success("Staff deleted successfully");
    navigate('/admin/people');
  } catch (error) {
    console.error("Delete failed:", error);
    toast.error("Failed to delete staff");
  }
};
  return (
    <>
    <div className="p-4 bg-light">
      {/* Tabs for People Type */}
  

      
                

      {!peopleTabKey && (
    
      <>
      <div className="p-4 bg-white rounded shadow-sm mb-4">
  {/* Header with Back & Action Buttons */}
  <div className="d-flex align-items-center mb-3">
    <div className="mb-3">
    <span
      className="text-primary d-inline-flex align-items-center"
      style={{ cursor: 'pointer' }}
      onClick={() => navigate(-1)}
    >
      <FaArrowLeft className="me-2" />
      <strong>Back to Staffs</strong>
    </span>
  </div>
    <div className="d-flex gap-2 ms-auto">
      <button
        className="btn btn-outline-secondary d-flex align-items-center"
        onClick={() => setShowEditModal(true)}
      >
        <FaEdit className="me-1" />
        Edit
      </button>
     <button
  className="btn btn-outline-danger d-flex align-items-center"
  onClick={handleDeleteStaff}
>
  <FaTrash className="me-1" />
  Delete
</button>

    </div>
  </div>

  {/* Staff Info Section */}
  <div className="d-flex align-items-center">
    <div
      className="rounded-circle text-white d-flex align-items-center justify-content-center me-3"
      style={{
        width: '60px',
        height: '60px',
        backgroundColor: 'skyblue',
        fontSize: '1.5rem',
      }}
    >
      {staff.name?.split(' ').map((n) => n[0]).join('').toUpperCase()}
    </div>
    <div className="flex-grow-1">
      <h2 className="mb-0">{staff.name}</h2>
      <h5 className="text-muted mb-1">{staff.designation}</h5>
      <div className="d-flex align-items-center gap-3 mt-1">
        <span className={`badge ${staff.status === 1 ? 'bg-success' : 'bg-danger'}`}>
          {staff.status === 1 ? 'Active' : 'Inactive'}
        </span>
      </div>
    </div>
  </div>
</div>

    <br/>

      {/* Quick Actions: Always Visible */}
      <Card className="mb-4 p-4 shadow-sm rounded-4">
  <h6 className="mb-3 text-dark fw-semibold">Quick Actions</h6>
 <Row className="gx-3 gy-3">
  {[
   { icon: <FaCalendarCheck />, label: "Mark Attendance", onClick: () => setShowAttendanceModal(true) },
    { icon: <FaPlane />, label: "Request Leave" ,onClick: () => setShowLeaveModal(true)},
    { icon: <FaFileInvoice />, label: "View Payslip", onClick:handlePayslipView },
    { icon: <FaTasks />, label: "Assign Task", onClick: () => setShowModal(true) },
  ].map((action, index) => (
    <Col key={index} xs={6} sm={4} md={3}>
      <div
        className="d-flex align-items-center gap-2 px-3 py-2 rounded-3 me-3"
        style={{
          backgroundColor: '#f8f9fa',
          fontWeight: 500,
          fontSize: '14px',
          color: '#2f3542',
          border: '1px solid #e3e6ea',
          cursor: 'pointer',
          transition: 'all 0.2s ease',
        }}
        onClick={action.onClick || null} // Only assign if it exists
      >
        <span style={{ fontSize: '16px' }}>{action.icon}</span>
        <span>{action.label}</span>
      </div>
    </Col>
  ))}
</Row>

</Card>


      {/* Staff Details Tabs: Always Visible */}
      <Card className="shadow-sm">
  <Tabs
    activeKey={tabKey}
    onSelect={(k) => setTabKey(k)}
    className="px-3 pt-3"
    style={{ borderBottom: '1px solid #dee2e6' }}
  >
   <Tab
  eventKey="general"
  title={
    <span className="d-flex align-items-center gap-1">
      <FaUser /> General
    </span>
  }
  className="p-3"
>
  {/* Personal & Role Information */}
  <h5 className="mb-3">Personal & Role Information</h5>
  <hr />
  <Row className="mb-3">
    <Col md={4}><strong>Organization:</strong><br />{staff.organization || 'N/A'}</Col>
    <Col md={4}><strong>Salutation:</strong><br />{staff.salutation || 'Mr./Ms.'}</Col>
    <Col md={4}><strong>Full Name:</strong><br />{staff.name}</Col>
  </Row>
  <Row className="mb-3">
    <Col md={4}><strong>Staff ID:</strong><br />{staff.id || 'N/A'}</Col>
    <Col md={4}><strong>Designation:</strong><br />{staff.designation || 'N/A'}</Col>
    <Col md={4}><strong>Staff Type:</strong><br />{staff.user_type || 'N/A'}</Col>
  </Row>
  <Row className="mb-3">
    <Col md={4}><strong>Date of Birth:</strong><br />{staff.date_of_birth || 'N/A'}</Col>
    <Col md={4}><strong>Blood Group:</strong><br />{staff.blood_group || 'N/A'}</Col>
    <Col md={4}><strong>Date of Joining:</strong><br />{staff.join_date || 'N/A'}</Col>
  </Row>

  {/* Contact Details */}
  <h5 className="mt-4 mb-3">Contact Details</h5>
  <hr />
  <Row className="mb-3">
    <Col md={4}><strong>Work Email:</strong><br />{staff.email || 'N/A'}</Col>
    <Col md={4}><strong>Personal Email:</strong><br />{staff.personal_email || 'N/A'}</Col>
    <Col md={4}><strong>Primary Phone:</strong><br />{staff.phone || 'N/A'}</Col>
  </Row>
  <Row className="mb-3">
    <Col md={4}><strong>Work Phone:</strong><br />{staff.work_phone || 'N/A'}</Col>
    <Col md={4}><strong>Emergency Contact 1:</strong><br />{staff.emergency_contact_1 || 'N/A'}</Col>
    <Col md={4}><strong>Emergency Contact 2:</strong><br />{staff.emergency_contact_2 || 'N/A'}</Col>
  </Row>
  <Row className="mb-3">
    <Col md={4}><strong>Parent's Mobile:</strong><br />{staff.parent_mobile || 'N/A'}</Col>
    <Col md={8}><strong>Address:</strong><br />{staff.address || 'N/A'}</Col>
  </Row>
</Tab>



   <Tab
  eventKey="profile"
  title={
    <span className="d-flex align-items-center gap-1">
      <FaIdBadge /> Profile
    </span>
  }
  className="p-3"
>
  <h5 className="mb-3">Professional Details</h5>
  <hr />

  <Row className="mb-3">
    <Col md={4}>
      <strong>Qualification:</strong><br />
      <span className="fw-semibold">{staff.qualification}</span>
    </Col>
    <Col md={4}>
      <strong>Experience:</strong><br />
      <span className="fw-semibold">{staff.experience}</span>
    </Col>
    <Col md={4}>
      <strong>Nature of Staff:</strong><br />
      <span className="fw-semibold">{staff.nature_of_staff}</span>
    </Col>
  </Row>

 <Row className="mb-3">
  <Col md={4}>
    <strong>COVID-19 Vaccinated:</strong><br />
    {staff.covid_vaccinated === 1 ? 'Yes' : 'No'}
  </Col>
</Row>


  <Row className="mb-3">
    <Col>
      <strong>Skills:</strong><br />
 <span className="fw-semibold">
  {(Array.isArray(staff.skills)
    ? staff.skills
    : JSON.parse(staff.skills || '[]')
  ).join(', ')}
</span>

    </Col>
  </Row>
</Tab>


    <Tab
  eventKey="documents"
  title={
    <span className="d-flex align-items-center gap-1">
      <FaFileAlt /> Documents
    </span>
  }
  className="p-3"
>
  <h5 className="mb-3">Uploaded Documents</h5>
  <hr />

 <Row className="gx-3 gy-3">
      {documents.map((doc, idx) => (
        <Col md={4} key={idx}>
          <div
            className="d-flex justify-content-between align-items-center px-3 py-2 border rounded"
            style={{
              backgroundColor: '#f8f9fa',
              borderLeft: `4px solid ${doc.uploaded ? 'green' : 'red'}`,
              boxShadow: '0 1px 2px rgba(0,0,0,0.05)',
            }}
          >
            <div className="d-flex align-items-center gap-2">
              <div
                style={{
                  width: '20px',
                  height: '20px',
                  borderRadius: '50%',
                  border: `2px solid ${doc.uploaded ? '#28a745' : '#dc3545'}`,
                  color: doc.uploaded ? '#28a745' : '#dc3545',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '14px',
                  fontWeight: 'bold',
                }}
              >
                {doc.uploaded ? '✔' : '✖'}
              </div>
              <span>{doc.name}</span>
            </div>
            <div>
              {doc.uploaded ? (
                <a href={doc.link} className="text-primary text-decoration-none fw-semibold" target="_blank" rel="noreferrer">View</a>
              ) : (
                <span className="text-muted">Missing</span>
              )}
            </div>
          </div>
        </Col>
      ))}
    </Row>
</Tab>


    <Tab
  eventKey="salary"
  title={
    <span className="d-flex align-items-center gap-1">
      <FaMoneyBillAlt /> Set Salary
    </span>
  }
  className="p-3"
>
  <Card className="p-4 shadow-sm">
    <h5 className="mb-4 border-bottom pb-2">Salary Structure</h5>
    <Row className="gy-4 gx-5">
      <Col md={4}>
        <div>
          <div style={{ fontSize: '0.875rem', color: '#6c757d' }}>Gross Salary / Month</div>
          <div style={{ fontWeight: 600, fontSize: '1.1rem', color: '#212529', marginTop: '4px' }}>
            ₹{staff?.daily_remuneration || '0.00'}
          </div>
        </div>
      </Col>
      <Col md={4}>
        <div>
          <div style={{ fontSize: '0.875rem', color: '#6c757d' }}>Rent Allowance</div>
          <div style={{ fontWeight: 600, fontSize: '1.1rem', color: '#212529', marginTop: '4px' }}>
            {staff?.rent_allowance_percent ? `${staff.rent_allowance_percent}%` : 'N/A'}
          </div>
        </div>
      </Col>
      <Col md={4}>
        <div>
          <div style={{ fontSize: '0.875rem', color: '#6c757d' }}>ESI Number</div>
          <div style={{ fontWeight: 600, fontSize: '1.1rem', color: '#212529', marginTop: '4px' }}>
            {staff?.esi_card_no || 'N/A'}
          </div>
        </div>
      </Col>
      <Col md={4}>
        <div>
          <div style={{ fontSize: '0.875rem', color: '#6c757d' }}>PF Number</div>
          <div style={{ fontWeight: 600, fontSize: '1.1rem', color: '#212529', marginTop: '4px' }}>
            {staff?.pf_no || 'N/A'}
          </div>
        </div>
      </Col>

      {/* Optional fields */}
      <Col md={4}>
        <div>
          <div style={{ fontSize: '0.875rem', color: '#6c757d' }}>Daily Remuneration</div>
          <div style={{ fontWeight: 600, fontSize: '1.1rem', color: '#212529', marginTop: '4px' }}>
            ₹{staff?.daily_remuneration || '0.00'}
          </div>
        </div>
      </Col>
    </Row>
  </Card>
</Tab>



  <Tab
  eventKey="leave"
  title={
    <span className="d-flex align-items-center gap-1">
      <FaUmbrellaBeach /> Leave
    </span>
  }
  className="p-3"
>
  <h5 className="mb-3">Leave History</h5>
  <hr />

  <div className="table-responsive">
    <table className="table table-hover align-middle">
      <thead className="table-light">
        <tr>
          <th>Type</th>
          <th>From</th>
          <th>To</th>
          <th>Reason</th>
          <th>Emergency</th>
          <th>Status</th>
          <th>Employee</th>
        </tr>
      </thead>
     <tbody>
  {staff?.leave_requests?.length > 0 ? (
    staff.leave_requests.map((leave, idx) => (
      <tr key={idx} className="bg-light">
        <td>{leave.leave_type}</td>
        <td>{leave.from_date}</td>
        <td>{leave.to_date}</td>
        <td>{leave.reason || '—'}</td>
        <td>
          <span
            className={`badge rounded-pill ${
              leave.is_emergency ? 'bg-danger text-white' : 'bg-secondary text-dark'
            }`}
          >
            {leave.is_emergency ? 'Yes' : 'No'}
          </span>
        </td>
        <td>
          <span
            className="badge"
            style={{
              backgroundColor:
                leave.status === 'APPROVED'
                  ? '#d4edda'
                  : leave.status === 'REJECTED'
                  ? '#f8d7da'
                  : '#fff3cd',
              color:
                leave.status === 'APPROVED'
                  ? '#155724'
                  : leave.status === 'REJECTED'
                  ? '#721c24'
                  : '#856404',
              padding: '0.4em 0.75em',
              borderRadius: '8px',
            }}
          >
            {leave.status}
          </span>
        </td>
        <td>{staff.name || '—'}</td>
      </tr>
    ))
  ) : (
    <tr>
      <td colSpan="7" className="text-center text-muted">
        No leave requests found.
      </td>
    </tr>
  )}
</tbody>

    </table>
  </div>
</Tab>




 <Tab
      eventKey="project"
      title={
        <span className="d-flex align-items-center gap-1">
          <FaTasks /> Project & Task
        </span>
      }
      className="p-3"
    >
      <h5 className="mb-3">Assigned Projects</h5>

      {loading ? (
        <p>Loading tasks...</p>
      ) : (
        <div className="table-responsive">
          <table className="table table-hover align-middle">
            <thead className="table-light">
              <tr>
                <th>Project Name</th>
                <th>Deadline</th>
                <th>Duration</th>
                <th>Value</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              {userTasks.length > 0 ? (
                userTasks.map((task, index) => (
                  <tr key={index}>
                    <td>{task.project_name || task.task_name}</td>
                    <td>{new Date(task.deadline).toLocaleDateString()}</td>
                    <td>{task.duration || 'N/A'}</td>
                    <td>
                      {task.project_value
                        ? `₹${parseFloat(task.project_value).toLocaleString()}`
                        : '—'}
                    </td>
                    <td>
                      <span
                        className="badge"
                        style={{
                          backgroundColor:
                            task.project_status === 'Completed'
                              ? '#d4edda'
                              : task.project_status === 'Pending'
                              ? '#fff3cd'
                              : '#cce5ff',
                          color:
                            task.project_status === 'Completed'
                              ? '#155724'
                              : task.project_status === 'Pending'
                              ? '#856404'
                              : '#004085',
                          padding: '0.4em 0.75em',
                          borderRadius: '8px',
                        }}
                      >
                        {task.project_status || '—'}
                      </span>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="5" className="text-center text-muted">
                    No tasks found
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      )}
    </Tab>



  <Tab
  eventKey="payslip"
  title={
    <span className="d-flex align-items-center gap-1">
      <FaFileInvoice /> Payslip
    </span>
  }
  className="p-3"
>
  <h5 className="mb-3">Payslip History</h5>

  <div className="table-responsive">
   <table className="table table-hover align-middle">
  <thead className="table-light">
    <tr>
      <th>Month</th>
      <th>Gross Salary</th>
      <th>Deductions</th>
      <th>Net Salary</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    {currentMonthPayslip && (
      <tr>
        <td>{currentMonthPayslip.month}</td>
        <td>₹{currentMonthPayslip.gross_salary}</td>
        <td>₹{currentMonthPayslip.deductions}</td>
        <td>₹{currentMonthPayslip.net_salary}</td>
        <td>
          <button
            className="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
            style={{ border: 'none' }}
            onClick={() => handleDownloadPayslip(currentMonthPayslip)}
          >
            <FaDownload /> Download
          </button>
        </td>
      </tr>
    )}
  </tbody>
</table>

  </div>
</Tab>

  </Tabs>
</Card>
</>
)}
<ViewPayslipModal
  show={showPayslipModal}
  onHide={() => setShowPayslipModal(false)}
  staff={staff}
/>
<Modal
  show={showEditModal}
  onHide={() => setShowEditModal(false)}
  size="lg"
  centered scrollable
>
  <Modal.Header closeButton>
    <Modal.Title className="fw-bold">Edit Staff</Modal.Title>
  </Modal.Header>
  <Modal.Body>
    {/* Add your form or content here */}
    <Card
  className="mb-4 p-3 shadow-sm border rounded bg-light"
  style={{ maxWidth: '840px' }} // Optional: center with margin: '0 auto'
>
  {/* First Row: Login toggle and Organization */}
  <Row className="mb-3">
    {/* Login Toggle */}
    <Col md={6} className="d-flex align-items-center">
      <Form.Label className="fw-semibold mb-0 me-3">
        Login is enabled
      </Form.Label>
      <Form.Check
        type="switch"
        id="login-switch"
        checked={loginEnabled}
        onChange={(e) => setLoginEnabled(e.target.checked)}
      />
    </Col>

    {/* Organization Dropdown */}
    <Col md={6}>
      <Form.Label className="fw-semibold">
        Organization <span className="text-danger">*</span>
      </Form.Label>
      <Form.Select
        value={organization}
        onChange={(e) => setOrganization(e.target.value)}
      >
        <option value="">Select Organization</option>
        <option>Bangalore Organization</option>
        <option>Kochi Organization</option>
        <option>Calicut Organization</option>
      </Form.Select>
    </Col>
  </Row>

  {/* Second Row: Username and Password */}
  <Row>
    <Col md={6}>
      <Form.Group className="mb-3">
        <Form.Label className="fw-semibold">
          Username <span className="text-danger">*</span>
        </Form.Label>
        <Form.Control
          type="text"
          value={username}
          onChange={(e) => setUsername(e.target.value)}
          placeholder="Enter username"
        />
      </Form.Group>
    </Col>

    <Col md={6}>
      <Form.Group className="mb-3">
        <Form.Label className="fw-semibold">
          Password <span className="text-danger">*</span>
        </Form.Label>
        <Form.Control
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          placeholder="Enter password"
        />
      </Form.Group>
    </Col>
  </Row>
</Card>
  <Accordion alwaysOpen>
             <Accordion.Item eventKey="0">
         <CustomAccordionToggle eventKey="0" icon={<FaUser />}>Staff Details</CustomAccordionToggle>
         <Accordion.Body className="pb-4 ps-4 pe-4">
             <StaffDetails data={staff} setData={setStaff} />
         </Accordion.Body>
         </Accordion.Item>
 
 
             <Accordion.Item eventKey="1">
         <CustomAccordionToggle eventKey="1" icon={<FaPhone />}>Contact Information</CustomAccordionToggle>
         <Accordion.Body className="pb-4 ps-4 pe-4">
             <ContactForm data={staff} setData={setStaff}/>
         </Accordion.Body>
         </Accordion.Item>
 
                 <Accordion.Item eventKey="2">
             <CustomAccordionToggle eventKey="2" icon={<FaDollarSign />}>Financial & HR Details</CustomAccordionToggle>
             <Accordion.Body className="pb-4 ps-4 pe-4">
                 <FinancialHR data={staff} setData={setStaff} />
             </Accordion.Body>
             </Accordion.Item>
 
 
       <Accordion.Item eventKey="3">
         <CustomAccordionToggle eventKey="3" icon={<FaCertificate />}>Skills</CustomAccordionToggle>
         <Accordion.Body className="pb-4 ps-4 pe-4"> <Skills data={selectedSkills} setData={setSelectedSkills}/> </Accordion.Body>
       </Accordion.Item>
 
      <Accordion.Item eventKey="4">
   <CustomAccordionToggle eventKey="4" icon={<FaFileAlt />}>Documents</CustomAccordionToggle>
 <Accordion.Body className="pb-4 ps-4">
   <div className="row">
     {[
       { id: "cv-upload", label: "CV" },
       { id: "padhar-front-upload", label: "Padhar Card (Front)" },
       { id: "dl-front-upload", label: "Driving License (Front)" },
       { id: "photo-upload", label: "Photo" },
       { id: "padhar-back-upload", label: "Padhar Card (Back)" },
       { id: "dl-back-upload", label: "Driving License (Back)" },
       { id: "passport-photo-upload", label: "Passport Size Photo" },
       { id: "pan-upload", label: "PAN Card" },
       { id: "passport-front-upload", label: "Passport (Front)" },
       { id: "passport-back-upload", label: "Passport (Back)" },
       { id: "pf-upload", label: "PF Document" },
       { id: "contract-upload", label: "Employee Contract" },
       { id: "esi-upload", label: "ESI Document" },
     ].map(({ id, label }) => (
       <div key={id} className="col-md-4 mb-4">
   <label className="form-label">{label}</label>
   <div className="file-upload-box">
     <input type="file" className="d-none" id={id} />
     <label htmlFor={id} className="file-upload-label text-center">
       <i className="fas fa-upload upload-icon mb-2"></i>
       <div className="upload-text">
         <strong>Click to upload</strong> or drag and drop
       </div>
     </label>
   </div>
 </div>
 
 
     ))}
   </div>
 </Accordion.Body>
 
 </Accordion.Item>
 
       <Accordion.Item eventKey="5">
   <CustomAccordionToggle eventKey="5" icon={<FaBuilding />}>Bank Details</CustomAccordionToggle>
   <Accordion.Body className="pb-4 ps-4">
   <div className="pb-4 ps-4 pe-4">
      <div className="row">
        <div className="col-md-6 mb-3">
          <label className="form-label">Date</label>
          <input
            type="date"
            className="form-control"
            name="date"
            value={staff.bank_document_date}
            onChange={handleChange}
          />
        </div>
        <div className="col-md-6 mb-3">
          <label className="form-label">Title</label>
          <input
            type="text"
            className="form-control"
            name="title"
            placeholder="Enter title"
            value={staff.bank_document_title}
            onChange={handleChange}
          />
        </div>

        <div className="col-md-12 mb-3">
          <label className="form-label">Description</label>
          <textarea
            className="form-control"
            name="description"
            rows="3"
            placeholder="Enter description"
            value={staff.bank_document_description}
            onChange={handleChange}
          />
        </div>

        <div className="col-md-6 mb-3">
          <label className="form-label">Bank Document</label>
          <div className="file-uploads-box">
            <input
              type="file"
              id="bank-doc-upload"
              className="d-none"
              onChange={handleFileChange}
            />
            <label htmlFor="bank-doc-upload" className="file-uploads-label text-center">
              <i className="fas fa-upload uploads-icon mb-2"></i>
              <div className="uploads-text">
                <strong>Click to upload</strong> or drag and drop
              </div>
              {staff.bank_document_file && (
                <div className="text-success mt-2">{staff.bank_document_file}</div>
              )}
            </label>
          </div>
        </div>
      </div>
    </div>
   </Accordion.Body>
 </Accordion.Item>
 
     </Accordion>
  </Modal.Body>
  <Modal.Footer style={{backgroundColor:"lightcyan"}}>
    <Button variant="secondary" onClick={() => setShowEditModal(false)}>
      Cancel
    </Button>
   <Button
  variant="primary"
  onClick={handleUpdateStaff}
>
  Edit Staffs
</Button>

  </Modal.Footer>
</Modal>

<Modal show={showModal} onHide={()=> setShowModal(false)} centered>
      <Modal.Header closeButton>
        <Modal.Title>Assign Task to {staff?.name}</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Form>
          <Form.Group controlId="task_name" className="mb-3">
            <Form.Label>Task Name</Form.Label>
            <Form.Control
              type="text"
              name="task_name"
              value={formData.task_name}
              onChange={handleChanges}
              required
            />
          </Form.Group>

          <Form.Group controlId="task_description" className="mb-3">
            <Form.Label>Task Description</Form.Label>
            <Form.Control
              as="textarea"
              name="task_description"
              value={formData.task_description}
              onChange={handleChanges}
              rows={3}
            />
          </Form.Group>

          <Form.Group controlId="deadline" className="mb-3">
            <Form.Label>Deadline</Form.Label>
            <Form.Control
              type="date"
              name="deadline"
              value={formData.deadline}
              onChange={handleChanges}
              required
            />
          </Form.Group>

          <Form.Group controlId="project_name" className="mb-3">
            <Form.Label>Project Name</Form.Label>
            <Form.Control
              type="text"
              name="project_name"
              value={formData.project_name}
              onChange={handleChanges}
            />
          </Form.Group>

          <Form.Group controlId="project_value" className="mb-3">
            <Form.Label>Project Value</Form.Label>
            <Form.Control
              type="number"
              name="project_value"
              value={formData.project_value}
              onChange={handleChanges}
            />
          </Form.Group>

          <Form.Group controlId="project_status" className="mb-3">
            <Form.Label>Project Status</Form.Label>
            <Form.Select
              name="project_status"
              value={formData.project_status}
              onChange={handleChanges}
            >
              <option value="">Select status</option>
              <option value="In Progress">In Progress</option>
              <option value="Completed">Completed</option>
              <option value="Pending">Pending</option>
            </Form.Select>
          </Form.Group>
        </Form>
      </Modal.Body>

      <Modal.Footer>
        <Button variant="secondary" onClick={()=> setShowModal(false)}>
          Cancel
        </Button>
        <Button variant="primary" onClick={handleSubmit}>
          Assign Task
        </Button>
      </Modal.Footer>
    </Modal>
        <Modal show={showLeaveModal} onHide={() => setShowLeaveModal(false)} centered   dialogClassName="square-modal">
  <Modal.Header closeButton>
    <Modal.Title>Request Leave</Modal.Title>
  </Modal.Header>
  <Modal.Body>
    <Form>
      <Form.Group className="mb-3">
        <Form.Label>Leave Type</Form.Label>
       <Form.Select
  name="leave_type"
  value={leaveForm.leave_type}
  onChange={(e) => setLeaveForm(prev => ({ ...prev, leave_type: e.target.value }))}
>
  <option value="">-- Select Leave Type --</option>
  <option>Annual Leave</option>
  <option>Sick Leave</option>
  <option>Maternity/Paternity Leave</option>
  <option>Bereavement Leave</option>
  <option>Casual Leave</option>
  <option>Paid Leave</option>
  <option>Unpaid Leave</option>
</Form.Select>

      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>From Date</Form.Label>
        <Form.Control
          type="date"
          name="from_date"
          value={leaveForm.from_date}
          onChange={(e) => setLeaveForm(prev => ({ ...prev, from_date: e.target.value }))}
        />
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>To Date</Form.Label>
        <Form.Control
          type="date"
          name="to_date"
          value={leaveForm.to_date}
          onChange={(e) => setLeaveForm(prev => ({ ...prev, to_date: e.target.value }))}
        />
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Reason</Form.Label>
        <Form.Control
          as="textarea"
          rows={2}
          name="reason"
          value={leaveForm.reason}
          onChange={(e) => setLeaveForm(prev => ({ ...prev, reason: e.target.value }))}
        />
      </Form.Group>

      <Form.Check
        type="checkbox"
        label="Emergency Leave"
        checked={leaveForm.is_emergency}
        onChange={(e) => setLeaveForm(prev => ({ ...prev, is_emergency: e.target.checked }))}
      />
    </Form>
  </Modal.Body>
  <Modal.Footer>
    <Button variant="secondary" onClick={() => setShowLeaveModal(false)}>
      Cancel
    </Button>
    <Button variant="primary" onClick={handleLeaveSubmit}>
      Submit Request
    </Button>
  </Modal.Footer>
</Modal>
<Modal show={showAttendanceModal} onHide={() => setShowAttendanceModal(false)}>
  <Modal.Header closeButton>
    <Modal.Title>Mark Attendance</Modal.Title>
  </Modal.Header>
  <Modal.Body>
    <div className="mb-3">
      <label className="form-label fw-semibold">Select Status:</label>
      <div className="d-flex gap-3">
        {["Present", "Absent", "Late"].map((status) => (
          <Button
            key={status}
            variant={attendanceStatus === status ? "primary" : "outline-secondary"}
            onClick={() => setAttendanceStatus(status)}
          >
            {status}
          </Button>
        ))}
      </div>
    </div>

    <hr />
    <h6 className="fw-bold mb-2">This Month's Attendance</h6>
    {loadingHistory ? (
      <p>Loading...</p>
    ) : attendanceHistory.length === 0 ? (
      <p className="text-muted">No attendance records yet.</p>
    ) : (
      <ul className="list-group small">
        {attendanceHistory.map((entry, i) => (
          <li className="list-group-item d-flex justify-content-between" key={i}>
            <span>{entry.date}</span>
            <span className={`badge bg-${getStatusColor(entry.status)}`}>{entry.status}</span>
          </li>
        ))}
      </ul>
    )}
  </Modal.Body>
  <Modal.Footer>
    <Button variant="secondary" onClick={() => setShowAttendanceModal(false)}>Close</Button>
    <Button variant="success" onClick={handleSubmitAttendance}>Submit</Button>
  </Modal.Footer>
</Modal>

            </div>
    </>
  );
}