import React, {
  useState,
  useEffect,
  useRef,
  forwardRef,
  useImperativeHandle
} from "react";
import {
  Table, Badge, Tabs, Tab, Button, Pagination, ButtonGroup,
  Modal, Dropdown, Form, Row, Col, Alert, Spinner,Card
} from "react-bootstrap";
import {
  FaEnvelope, FaEdit, FaTrash, FaBan, FaFileAlt,
  FaFilePdf, FaFileWord, FaFileImage, FaDownload, FaCheckCircle,
  FaUser, FaBriefcase, FaMoneyBill, FaGift, FaClock, FaCalendarAlt,
  FaUserCheck, FaUserTimes, FaUserClock, FaHistory, FaEye,
  FaPlus, FaFileContract, FaPaperclip, FaUpload, FaIdCard, FaRobot,
  FaFileMedical, FaUserShield, FaBalanceScale, FaWallet,
  FaPercentage, FaPassport, FaTicketAlt, FaComment, FaEllipsisH, FaSave, FaTimes
} from "react-icons/fa";
import { FiCalendar, FiAlertTriangle, FiCheckCircle } from "react-icons/fi";
import { toast } from "react-toastify";
import axios from "axios";
import html2pdf from "html2pdf.js";
import "./Table.css";
import jsPDF from 'jspdf';
import EmployeeBenefits from "./EmployeeBenefits";
import { Umbrella } from 'lucide-react'; // or any similar icon
const Tables = forwardRef((props, ref) => {
  const slipRef = useRef();
  const [showBenefitsModal, setShowBenefitsModal] = useState(false);

  const [showEdit, setShowEdit] = useState(false);
  const [selectedEmpId, setSelectedEmpId] = useState(null);
const handleFileChange = (e) => {
  const { name, files } = e.target;
  setFormData((prev) => ({
    ...prev,
    [name]: files[0], // single file per input
  }));
};

const handleCheckboxChange = (e) => {
  const { name, checked } = e.target;
  setFormData((prev) => ({
    ...prev,
    [name]: checked,
  }));
};  
const handleShow = () => setShowModal(true);
 const [activeItem, setActiveItem] = useState(null);
  const [viewItem, setViewItem] = useState(null);
  

  const benefitItems = [
    { id: 'esi', label: 'ESI', icon: <FaFileMedical size={16} /> },
    { id: 'insurance', label: 'Insurance Details', icon: <FaUserShield size={16} /> },
    { id: 'leave', label: 'Leave Balances', icon: <FaBalanceScale size={16} /> },
    { id: 'stock', label: 'Stock Options', icon: <FaBriefcase size={16} /> },
    { id: 'loan', label: 'Loan Information', icon: <FaWallet size={16} /> },
    { id: 'pf', label: 'Provident Fund', icon: <FaMoneyBill size={16} /> },
    { id: 'gratuity', label: 'Gratuity', icon: <FaPercentage size={16} /> },
    { id: 'bonus', label: 'Bonus', icon: <FaGift size={16} /> },
    { id: 'incentives', label: 'Incentives', icon: <FaPercentage size={16} /> },
    { id: 'visa', label: 'Visa', icon: <FaPassport size={16} /> },
    { id: 'travel', label: 'Travel Tickets', icon: <FaTicketAlt size={16} /> },
    { id: 'feedback', label: 'Feedback', icon: <FaComment size={16} /> },
    { id: 'other', label: 'Other Benefits', icon: <FaEllipsisH size={16} /> }
  ];

  const handleClose = () => {
  setShowBenefitsModal(false);
  setViewItem(null);
  setActiveItem(null);
};

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    alert(`Submitting: ${JSON.stringify(formData)}`);
    setActiveItem(null);
  };
const handlePrintSlip = () => {
  const element = slipRef.current;
  const auditDiv = document.querySelector('.no-print');

  // Temporarily hide the AI Payroll Audit div
  if (auditDiv) auditDiv.style.display = 'none';

  const opt = {
    margin: 0.5,
    filename: `Payroll_${state.selectedEmployee?.first_name}_${state.payrollMonth}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  };

  html2pdf().set(opt).from(element).save().then(() => {
    // Restore the div after PDF is generated
    if (auditDiv) auditDiv.style.display = '';
  });
};


const [formData, setFormData] = useState({
  // Personal Info
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  dob: '',
  gender: '',
  address: '',

  // Emergency Contact
  contactName: '',
  relationship: '',
  contactPhone: '',
  contactEmail: '',

  // Employment Info
  position: '',
  department: '',
  employment_type: '',
  hire_date: '',
  reporting_manager: '',
  work_location: '',

  // Employment History (1st entry, others go into employmentHistory array)
  responsibilities: '',
  previous_company: '',
  previous_start_date: '',
  previous_end_date: '',
  previous_position: '',

  // 💰 Salary Fields
  base_salary: '',
  pay_frequency: '',
  housing_allowance: '',
  transport_allowance: '',
  medical_allowance: '',
  other_allowances: '',

  // 🏦 Payment Info
  bank_name: '',
  account_number: '',
  routing_number: '',
  payment_method: '',
  documents_authentic: false,
resume: null,
id_proof: null,
employment_contract: null,
medical_certificate: null,
education_certificates: null,

});


const [positions, setPositions] = useState([]);
const [departments, setDepartments] = useState([]);
const [enumData, setEnumData] = useState({}); // user_type enums
useEffect(() => {
  const fetchDropdowns = async () => {
    try {
      const [positionsRes, departmentsRes, enumsRes] = await Promise.all([
        fetch("/api/v1/positions"),
        fetch("/api/v1/departments"),
        fetch("/api/v1/enums/users")
      ]);

      const [positionsData, departmentsData, enumsData] = await Promise.all([
        positionsRes.json(),
        departmentsRes.json(),
        enumsRes.json()
      ]);

      console.log("Positions API:", positionsData);
      console.log("Departments API:", departmentsData);
      console.log("Enums API:", enumsData);

      setPositions(positionsData.positions); // ✅ correct // fallback if no .data
      setDepartments(departmentsData.data ?? departmentsData);
      setEnumData(enumsData);
    } catch (error) {
      console.error("Error fetching dropdown data:", error);
    }
  };

  fetchDropdowns();
}, []);


  const [employmentHistory, setEmploymentHistory] = useState([
  {
    previous_company: '',
    start_date: '',
    end_date: '',
    position: '',
    responsibilities: ''
  }
]);
const [activeTab, setActiveTab] = useState('personal');
const [statusLoading, setStatusLoading] = useState(false);
const handleNext = () => {
  if (activeTab === 'personal') setActiveTab('employment');
  else if (activeTab === 'employment') setActiveTab('salary');
  else if (activeTab === 'salary') setActiveTab('documents');
};

const handleAddExperience = () => {
  setEmploymentHistory([...employmentHistory, {
    previous_company: '',
    start_date: '',
    end_date: '',
    position: '',
    responsibilities: ''
  }]);
};

const handleRemoveExperience = (index) => {
  const updated = [...employmentHistory];
  updated.splice(index, 1);
  setEmploymentHistory(updated);
};

  const handleEdit = (id) => {
    setSelectedEmpId(id);
    setShowEdit(true);
  };


useEffect(() => {
  if (selectedEmpId) {
    axios.get(`/api/v1/users/${selectedEmpId}`)
      .then(res => {
        const data = res.data;

        // ✅ Set form data - Personal, Emergency, Employment, Salary, Payment, Docs
        setFormData({
          // Personal Info
          first_name: data.first_name || '',
          last_name: data.last_name || '',
          email: data.email || '',
          phone: data.phone || '',
          dob: formatDate(data.date_of_birth), // ✅ make sure formatDate handles null
          gender: data.gender || '',
          address: data.address || '',

          // Emergency Contact
          contactName: data.emergency_contact_name || '',
          relationship: data.emergency_contact_relationship || '',
          contactPhone: data.emergency_contact_phone || '',
          contactEmail: data.emergency_contact_email || '',

          // Employment Info
          position: data.position || '',
          department: data.department || '',
          employment_type: data.employment_type || '',
          hire_date: formatDate(data.hire_date),
          reporting_manager: data.reporting_manager || '',
          work_location: data.work_location || '',

          // Salary Fields
          base_salary: data.base_salary || '',
          pay_frequency: data.pay_frequency || '',
          housing_allowance: data.housing_allowance || '',
          transport_allowance: data.transport_allowance || '',
          medical_allowance: data.medical_allowance || '',
          other_allowances: data.other_allowances || '',

          // Payment Info
          bank_name: data.bank_name || '',
          account_number: data.account_number || '',
          routing_number: data.routing_number || '',
          payment_method: data.payment_method || '',

          // Documents (initialize empty for update form)
          documents_authentic: false,
          resume: null,
          id_proof: null,
          employment_contract: null,
          medical_certificate: null,
          education_certificates: null,
        });

        // ✅ Employment History
        setEmploymentHistory(
          (data.employment_histories || []).map((exp) => ({
            previous_company: exp.previous_company || '',
            previous_start_date: formatDate(exp.previous_start_date),
            previous_end_date: formatDate(exp.previous_end_date),
            previous_position: exp.previous_position || '',
            previous_responsibilities: exp.previous_responsibilities || ''
          }))
        );
      })
      .catch(err => console.error("Error fetching employee:", err));
  }
}, [selectedEmpId]);



const formatDate = (dateString) => {
  return dateString ? new Date(dateString).toISOString().split('T')[0] : '';
};


  const handleUpdate = async () => {
    try {
      await axios.put(`/api/v1/users/${selectedEmpId}`, formData);
      setShowEdit(false);
      fetchEmployees();
      toast.success("Employee updated successfully");
    } catch (err) {
      console.error("Update failed:", err.response?.data || err);
      toast.error("Failed to update employee");
    }
  };

  // State management
  const [state, setState] = useState({
    showPayrollModal: false,
    showLeaveModal: false,
    showMessageModal: false,
    showDocumentModal: false,
    employees: [],
    documents: [],
    selected: [],
    selectedEmployee: null,
    selectedEmployeeId: null,
    showUploadInput: false,
    uploading: false,
    loading: {
      employees: true,
      employee: false,
      documents: false
    },
    leaveData: {
      leaveType: "",
      fromDate: "",
      toDate: "",
      reason: "",
      isEmergency: false
    },
    messageData: {
      subject: "",
      content: "",
      priority: "normal"
    },
    newDocument: {
      title: "",
      file: null
    },
    payrollDetails: null,
    payrollMonth: new Date(),
    attendance:""
  });

  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 25;
  const [searchTerm, setSearchTerm] = useState("");

  // Constants
 const [leaveTypes, setLeaveTypes] = useState([]);
 
   useEffect(() => {
     const fetchLeaveTypes = async () => {
       try {
         const response = await axios.get("/api/v1/leave-requests/enums");
         setLeaveTypes(response.data.leave_type || []);
       } catch (error) {
         console.error("Error fetching leave types:", error);
       }
     };
 
     fetchLeaveTypes();
   }, []);

  const [priorities, setPriorities] = useState([]);

useEffect(() => {
  const fetchPriorities = async () => {
    try {
      const res = await axios.get("/api/messages/priorities");
      setPriorities(res.data.priorities || []);
    } catch (error) {
      console.error("Error fetching priorities:", error);
    }
  };

  fetchPriorities();
}, []);

  const iconStyle = {
    backgroundColor: '#800080',
    borderRadius: '50%',
    padding: '6px',
    fontSize:"15px",
    color: 'white',
    marginRight: '8px',
  };

  const tabLabel = (Icon, label) => (
    <span style={{ display: 'flex', alignItems: 'center' }}>
      <Icon style={iconStyle} />
      <span>{label}</span>
    </span>
  );
  const tabKeys = ["personal", "employment", "salary", "documents"];

  // API endpoints
  const API_ENDPOINTS = {
    employees: "/api/v1/employees",
    employee: (id) => `/api/v1/employees/${id}`,
    documents: (id) => `/api/v1/employee/${id}/documents`,
    upload: (id) => `/api/v1/employee/${id}/upload-document`
  };

  // Fetch all employees
  const fetchEmployees = async () => {
    try {
      setState(prev => ({ ...prev, loading: { ...prev.loading, employees: true } }));
      const res = await fetch(API_ENDPOINTS.employees);
      const data = await res.json();

      if (res.ok) {
        setState(prev => ({
          ...prev,
          employees: data.data || [],
          loading: { ...prev.loading, employees: false }
        }));
      } else {
        console.error("Failed to fetch employees:", data.message);
        setState(prev => ({ ...prev, loading: { ...prev.loading, employees: false } }));
      }
    } catch (error) {
      console.error("Failed to fetch employees:", error);
      setState(prev => ({ ...prev, loading: { ...prev.loading, employees: false } }));
    }
  };

  // Fetch single employee
  const fetchEmployee = async (id) => {
    try {
      setState(prev => ({...prev, loading: {...prev.loading, employee: true}}));
      const res = await fetch(API_ENDPOINTS.employee(id));
      const data = await res.json();
      
      if (res.ok) {
        setState(prev => ({
          ...prev,
          selectedEmployee: data,
          loading: {...prev.loading, employee: false}
        }));
      } else {
        console.error("Failed to fetch employee:", data.message);
        setState(prev => ({...prev, loading: {...prev.loading, employee: false}}));
      }
    } catch (error) {
      console.error("Failed to fetch employee:", error);
      setState(prev => ({...prev, loading: {...prev.loading, employee: false}}));
    }
  };

  // Fetch documents
  const fetchDocuments = async (employeeId) => {
    try {
      setState(prev => ({...prev, loading: {...prev.loading, documents: true}}));
      const res = await fetch(API_ENDPOINTS.documents(employeeId));
      const data = await res.json();
      
      if (res.ok) {
        setState(prev => ({
          ...prev,
          documents: data.data || [],
          loading: {...prev.loading, documents: false}
        }));
      } else {
        console.error("Failed to fetch documents:", data.message);
        setState(prev => ({...prev, loading: {...prev.loading, documents: false}}));
      }
    } catch (error) {
      console.error("Failed to fetch documents:", error);
      setState(prev => ({...prev, loading: {...prev.loading, documents: false}}));
    }
  };

  // Upload document
  const uploadDocument = async () => {
    const { selectedEmployeeId, newDocument } = state;
    
    if (!newDocument.title || !newDocument.file) {
      alert("Please provide both title and file");
      return;
    }

    try {
      setState(prev => ({...prev, uploading: true}));
      
      const formData = new FormData();
      formData.append("title", newDocument.title);
      formData.append("type", "custom");
      formData.append("file", newDocument.file);

      const res = await fetch(API_ENDPOINTS.upload(selectedEmployeeId), {
        method: "POST",
        body: formData
      });

      const data = await res.json();
      
      if (res.ok) {
        await fetchDocuments(selectedEmployeeId);
        setState(prev => ({
          ...prev,
          showUploadInput: false,
          uploading: false,
          newDocument: { title: "", file: null }
        }));
        toast.success("Document uploaded successfully");
      } else {
        throw new Error(data.message || "Upload failed");
      }
    } catch (error) {
      console.error("Upload error:", error);
      toast.error(`Upload failed: ${error.message}`);
      setState(prev => ({...prev, uploading: false}));
    }
  };

 
const handleChange = (e) => {
  const { name, value, type, checked, files } = e.target;

  const fieldValue = type === "checkbox"
    ? checked
    : type === "file"
    ? files[0] || null
    : value;

  setState(prevState => ({
    ...prevState,
    leaveData: {
      ...prevState.leaveData,
      [name]: fieldValue
    },
    messageData: {
      ...prevState.messageData,
      [name]: fieldValue
    }
  }));
};



const handleExperienceChange = (index, field, value) => {
  const updated = [...employmentHistory];
  updated[index][field] = value;
  setEmploymentHistory(updated);
};


  // Handle leave submission
  const handleSubmitLeave = async (e) => {
    e.preventDefault();

    const payload = {
      employee_id: state.selectedEmployee?.id,
      leave_type: state.leaveData.leaveType,
      from_date: state.leaveData.fromDate,
      to_date: state.leaveData.toDate,
      reason: state.leaveData.reason,
      is_emergency: state.leaveData.isEmergency,
      status: 'PENDING'
    };

    try {
      const res = await fetch("api/v1/leave/apply", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) {
        const error = await res.json();
        console.error(error);
        toast.error("Leave submission failed.");
        return;
      }

      toast.success("Leave submitted successfully.");
      setState(prev => ({ ...prev, showLeaveModal: false }));

    } catch (err) {
      console.error("Error submitting leave:", err);
      toast.error("Something went wrong.");
    }
  };

  const handleAutoApprove = async () => {
    const payload = {
      employee_id: state.selectedEmployee?.id,
      leave_type: state.leaveData.leaveType,
      from_date: state.leaveData.fromDate,
      to_date: state.leaveData.toDate,
      reason: state.leaveData.reason,
      is_emergency: state.leaveData.isEmergency,
      status: 'APPROVED'
    };

    try {
      const res = await fetch("api/v1/leave/apply", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) {
        const error = await res.json();
        console.error(error);
        toast.error("Auto-approval failed.");
        return;
      }

      toast.success("Leave auto-approved!");
      setState(prev => ({ ...prev, showLeaveModal: false }));
    } catch (err) {
      console.error("Error in auto-approval:", err);
      toast.error("Something went wrong.");
    }
  };

  // Handle message submission
  const handleSubmitMessage = async (e) => {
    e.preventDefault();

    const payload = {
      user_id: state.selectedEmployee?.id,
      subject: state.messageData.subject,
      content: state.messageData.content,
      priority: state.messageData.priority
    };

    try {
      const response = await fetch("/api/v1/send-message", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        body: JSON.stringify(payload)
      });

      if (!response.ok) {
        const error = await response.json();
        toast.error("Failed to send message.");
        console.error(error);
        return;
      }

      toast.success("Message sent successfully!");

      setState(prev => ({
        ...prev,
        showMessageModal: false,
        messageData: {
          subject: '',
          content: '',
          priority: 'normal'
        }
      }));

    } catch (err) {
      console.error("Error:", err);
      toast.error("Something went wrong!");
    }
  };

  // Initialize component
  useEffect(() => {
    fetchEmployees();
  }, []);

  const handleDeleteEmployee = async (employeeId) => {
    if (!employeeId) {
      toast.error("No employee selected.");
      return;
    }

    if (!window.confirm("Are you sure you want to delete this employee?")) return;

    try {
      const response = await fetch(`/api/v1/users/${employeeId}`, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        }
      });

      if (!response.ok) {
        const errorData = await response.json();
        toast.error(errorData.message || "Failed to delete employee.");
        return;
      }

      toast.success("Employee deleted successfully!");

      setState(prev => ({
        ...prev,
        selectedEmployee: null,
        employees: prev.employees.filter(emp => emp.id !== employeeId)
      }));

    } catch (err) {
      console.error(err);
      toast.error("Something went wrong.");
    }
  };

  const fetchPayrollDetails = async (employee) => {
    try {
      const res = await fetch(`/api/employee/${employee.id}/payroll`);
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "Failed to fetch payroll");

      setState(prev => ({
        ...prev,
        selectedEmployee: employee,
        payrollDetails: data,
        payrollMonth: data.month || new Date(),
        showPayrollModal: true
      }));
    } catch (err) {
      console.error(err);
      toast.error("Unable to load payroll details");
    }
  };

      const filteredEmployees = state.employees.filter(emp => {
      const search = searchTerm.toLowerCase();
      const fullName = `${emp.first_name || ''} ${emp.last_name || ''}`.toLowerCase();

      return (
        (emp.first_name?.toLowerCase().includes(search)) ||
        (emp.last_name?.toLowerCase().includes(search)) ||
        (emp.emp_code?.toLowerCase().includes(search)) ||
        fullName.includes(search)
      );
    });


  const paginatedData = filteredEmployees.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  // Helper functions
  const getFileIcon = (fileType) => {
    switch(fileType) {
      case 'pdf': return <FaFilePdf className="text-danger" />;
      case 'word': return <FaFileWord className="text-primary" />;
      case 'image': return <FaFileImage className="text-success" />;
      default: return <FaFileAlt />;
    }
  };
const handleStatusChange = async (userId, status) => {
  if (statusLoading) return;
  try {
    setStatusLoading(true);
    const response = await fetch(`/api/v1/users/${userId}/attendance`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ status }),
    });

    const data = await response.json();
    if (data.success) {
      toast.success("Attendance status updated successfully");

      // ✅ Update local state: set emp.attendance = new status
      setState(prev => ({
        ...prev,
        employees: prev.employees.map(emp =>
          emp.id === userId ? { ...emp, attendance: status } : emp
        )
      }));

     
       await fetchEmployees();
    } else {
      toast.error("Failed to update attendance");
    }
  } catch (error) {
    console.error("Error updating status:", error);
    toast.error("Something went wrong while updating status");
  } finally {
    setStatusLoading(false);
  }
};


  const unsuspendEmployee = async (empId) => {
    try {
      const res = await fetch(`/api/v1/employees/${empId}/unsuspend`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });

      const data = await res.json();
      if (res.ok) {
        toast.success(data.message || "Employee unsuspended successfully");
        setState(prev => ({
          ...prev,
          employees: prev.employees.map(emp =>
            emp.id === empId ? { ...emp, suspend: false } : emp
          )
        }));
        fetchEmployees();
      } else {
        toast.error(data.message || "Failed to unsuspend employee");
      }
    } catch (error) {
      console.error("Error:", error);
      toast.error("Something went wrong while unsuspending.");
    }
  };

  const suspendEmployee = async (empId) => {
    try {
      const res = await fetch(`/api/v1/employees/${empId}/suspend`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });

      const data = await res.json();

      if (res.ok) {
        toast.success(data.message || "Employee suspended successfully");
        setState(prev => ({
          ...prev,
          employees: prev.employees.map(emp =>
            emp.id === empId ? { ...emp, suspend: true } : emp
          )
        }));
        fetchEmployees();
      } else {
        toast.error(data.message || "Failed to suspend employee");
      }
    } catch (error) {
      console.error("Error:", error);
      toast.error("Something went wrong while suspending.");
    }
  };

  const openDocumentModal = (employeeId) => {
    setState(prev => ({
      ...prev,
      selectedEmployeeId: employeeId,
      showDocumentModal: true
    }));
    fetchEmployee(employeeId);
    fetchDocuments(employeeId);
  };

  // Styles
  const styles = {
    actionBtn: {
      padding: "4px 8px",
      minWidth: "20px",
      height: "25px",
      display: "flex",
      alignItems: "center",
      justifyContent: "center"
    },
    tableCell: {
      padding: "8px 0",
      color: "#333"
    },
    valueCell: {
      padding: "8px 0",
      textAlign: "right",
      fontWeight: "500"
    },
    totalLabel: {
      padding: "10px 0",
      fontWeight: "bold",
      color: "#1b5e20"
    },
    totalValue: {
      padding: "10px 0",
      textAlign: "right",
      fontWeight: "bold",
      color: "#1b5e20"
    }
  };
useImperativeHandle(ref, () => ({
  getExportData: () => {
    return paginatedData.map(emp => ({
      ID: emp.id,
      "EMP CODE":`EMP ${emp.id}`,
      Name: `${emp.first_name || ""} ${emp.last_name || ""}`.trim(),
      Email: emp.email || "N/A",
      Phone: emp.phone || "N/A",
      Department: emp.department_relation?.department_name || "N/A",
      Position: emp.position || "N/A",
      "Base Salary": emp.base_salary || "N/A",
      "Leave Taken": emp.leave_days ?? "N/A",
      "Total Leaves": emp.total_leaves ?? "N/A",
      "Leave Days": emp.leave_days ?? "N/A",
      "Daily Hours": emp.daily_hours ?? "N/A",
      "Monthly Hours": emp.monthly_hours ?? "N/A",
      "Status": emp.status === 1 ? "Active" : "Inactive"


    }));
  }
}));



  return (
    <div className="p-3">
      <div className="d-flex justify-content-end mb-3" style={{ maxWidth: "600px", marginLeft: "auto" }}>
        <Form.Control
          type="text"
          placeholder="Search employees by name"
          value={searchTerm}
          onChange={e => setSearchTerm(e.target.value)}
          style={{ width: "100%" }}
        />
      </div>

      {/* Main Table */}
      <div className="p-3 mx-0">
        <div className="d-flex align-items-center mb-3" style={{ fontSize: "12px" }}>
          <input 
            type="checkbox" 
            className="me-2"
            checked={state.selected.length === state.employees.length && state.employees.length > 0}
            onChange={(e) => {
              setState(prev => ({
                ...prev,
                selected: e.target.checked ? prev.employees.map(emp => emp.id) : []
              }));
            }}
          />
          <strong>{state.selected.length} Selected</strong>
        </div>

        <Table striped bordered hover responsive style={{ fontSize: "10px" }}>
          <thead style={{ backgroundColor: "#e9ecef" }}>
            <tr>
              <th style={{ width: "10%", textAlign: "center" }}>#</th>
              <th style={{ width: "10%" }}>Employee</th>
              <th style={{ width: "5%" }}>Department</th>
              <th style={{ width: "5%" }}>Position</th>
              <th style={{ width: "5%" }}>Status</th>
              <th style={{ width: "5%" }}>Attendance</th>
              <th style={{ width: "5%", textAlign: "right" }}>Payroll</th>
              <th style={{ width: "5%" }}>Leaves</th>
              <th style={{ width: "25%" }}>Actions</th>
            </tr>
          </thead>
          <tbody>
            {state.loading.employees ? (
              <tr>
                <td colSpan="9" className="text-center">
                  <Spinner animation="border" size="sm" /> Loading employees...
                </td>
              </tr>
            ) : filteredEmployees.length === 0 ? (
              <tr>
                <td colSpan="9" className="text-center">No employees found</td>
              </tr>
            ) : (
              paginatedData.map(emp => (
                <tr key={emp.id} style={{ height: "38px" }}>
                  <td style={{ textAlign: "center" }}>
                    <input
                      type="checkbox"
                      checked={state.selected.includes(emp.id)}
                      onChange={(e) => {
                        setState(prev => ({
                          ...prev,
                          selected: e.target.checked
                            ? [...prev.selected, emp.id]
                            : prev.selected.filter(id => id !== emp.id)
                        }));
                      }}
                    />
                  </td>

                  <td style={{ fontWeight: "500", wordBreak: "break-word" }}>
                    {emp.emp_code} {emp.first_name} {emp.last_name}
                  </td>

                  <td>{emp.department_relation?.department_name || 'N/A'}</td>

                  <td>{emp.position}</td>

                  <td>
                    <Badge
                      bg={emp.status === 1 ? "success" : "secondary"}
                      style={{ fontSize: "12px" }}
                    >
                      {emp.status === 1 ? "Active" : "Inactive"}
                    </Badge>
                  </td>

                  <td>
                    <div className="d-flex align-items-center" style={{ minWidth: "100px" }}>
                      <ButtonGroup
                       
                        style={{
                              border: "1px solid #dee2e6",        // light grey border
                              padding: "2px",
                              display: "flex",
                              alignItems: "center",
                              fontSize: "10px",
                              color: "#495057",                   // soft dark grey text
                              backgroundColor: "#f8f9fa"          // Bootstrap light grey background
                            }}

                      >
                        {/* Clock + Daily Hours */}
                        <div className="d-flex align-items-center" style={{ gap: "4px" }}>
                          <FaClock size={12} color="#6c757d" />
                          <span>{emp.daily_hours || "0h/d"}</span>
                        </div>

                        {/* Separator */}
                        <div style={{ padding: "0 6px", color: "#ccc" }}>|</div>

                        {/* Calendar + Monthly Hours */}
                        <div className="d-flex align-items-center" style={{ gap: "4px" }}>
                          <FaCalendarAlt size={12} color="#6c757d" />
                          <span>
                            {(emp.working_days ? `${emp.working_days}d/m` :
                              emp.leave_days ? `${emp.leave_days}d/m` : "0d/m")}
                          </span>
                        </div>

                        {/* Separator */}
                        <div style={{ padding: "0 6px", color: "#ccc" }}>|</div>

                        {/* Attendance Dropdown */}
                        <Dropdown as={ButtonGroup}>
                          <Dropdown.Toggle
                            variant="light"
                            size="sm"
                            style={{
                              padding: "0 8px",
                              fontWeight: "500",
                              fontSize: "11px",
                              lineHeight: "1",
                              border: "none",
                              backgroundColor: "transparent",
                              color: "#000"
                            }}
                          >
                            {(emp.attendance || "").charAt(0).toUpperCase() || "P"}
                          </Dropdown.Toggle>

                          <Dropdown.Menu>
                            <Dropdown.Item onClick={() => handleStatusChange(emp.id, 'present')}>
                              <FaUserCheck className="me-2 text-success" /> Present
                            </Dropdown.Item>
                            <Dropdown.Item onClick={() => handleStatusChange(emp.id, 'absent')}>
                              <FaUserTimes className="me-2 text-danger" /> Absent
                            </Dropdown.Item>
                            <Dropdown.Item onClick={() => handleStatusChange(emp.id, 'late')}>
                              <FaUserClock className="me-2 text-warning" /> Late
                            </Dropdown.Item>
                          </Dropdown.Menu>
                        </Dropdown>
                      </ButtonGroup>
                    </div>
                  </td>



                  <td
                    style={{ ...styles.valueCell, cursor: "pointer" }}
                    onClick={() => fetchPayrollDetails(emp)}
                  >
                    ₹{emp.base_salary?.toLocaleString() || "0"}
                  </td>

               <td style={{ cursor: 'pointer' }}>
  <Dropdown>
    <Dropdown.Toggle
      variant="light"
      id={`dropdown-${emp.id}`}
      style={{
        color: '#00A0B5',
        border: '1px solid #00A0B5',
        borderRadius: '5px',
        width: "60px",
        padding: '4px 12px',
        backgroundColor: 'white',
        fontWeight: '500',
        fontSize: '10px',
        display: 'flex',
        alignItems: 'center',
        gap: '5px'
      }}
    >
      {emp.leave_type_counts?.['Annual Leave']||0 }/{emp.leave_type_counts?.['Sick Leave']||0}
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="10"
        height="10"
        fill="#00A0B5"
        viewBox="0 0 16 16"
      >
        <path d="M1.5 5.5l6 6 6-6" />
      </svg>
    </Dropdown.Toggle>

    <Dropdown.Menu style={{ minWidth: '200px', padding: '10px' }}>
      <div
        style={{
          fontWeight: '600',
          color: '#6c757d',
          fontSize: '13px',
          padding: '5px 12px'
        }}
      >
        Leave Balance
      </div>

      <Dropdown.ItemText style={{ fontSize: '14px', paddingLeft: '12px' }}>
        Casual: {emp.leave_type_counts?.['Annual Leave'] || 0}
      </Dropdown.ItemText>
      <Dropdown.ItemText style={{ fontSize: '14px', paddingLeft: '12px' }}>
        Sick: {emp.leave_type_counts?.['Sick Leave'] || 0}
      </Dropdown.ItemText>

      <Dropdown.Divider />

      <Dropdown.Item
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: '8px',
          fontSize: '14px',
          paddingLeft: '12px'
        }}
        onClick={() =>
          setState((prev) => ({
            ...prev,
            selectedEmployee: emp,
            showLeaveModal: true
          }))
        }
      >
        <Umbrella size={16} />
        New Request
      </Dropdown.Item>
    </Dropdown.Menu>
  </Dropdown>
</td>





                  <td>
                    <div className="d-flex justify-content-center gap-1 flex-wrap">
                      <Button
                        variant="outline-primary"
                        size="sm"
                        title="Message"
                        style={styles.actionBtn}
                        onClick={() =>
                          setState(prev => ({
                            ...prev,
                            selectedEmployee: {
                              ...emp,
                              name: `${emp.first_name} ${emp.last_name}`
                            },
                            showMessageModal: true
                          }))
                        }
                      >
                        <FaEnvelope />
                      </Button>

                      <Button
                        variant="outline-secondary"
                        size="sm"
                        title="Edit"
                        style={styles.actionBtn}
                        onClick={() => handleEdit(emp.id)}
                      >
                        <FaEdit />
                      </Button>

                      <Button
                        variant="outline-danger"
                        size="sm"
                        title="Delete"
                        style={styles.actionBtn}
                        onClick={() => handleDeleteEmployee(emp.id)}
                      >
                        <FaTrash />
                      </Button>

                      <Button
                        variant={emp.suspend === 1 ? "outline-success" : "outline-warning"}
                        size="sm"
                        title={emp.suspend === 1 ? "Unsuspend" : "Suspend"}
                        onClick={() => (emp.suspend === 1 ? unsuspendEmployee(emp.id) : suspendEmployee(emp.id))}
                      >
                        {emp.suspend === 1 ? <FaCheckCircle /> : <FaBan />}
                      </Button>

                      <Button
                        variant="outline-success"
                        size="sm"
                        title="View Documents"
                        style={styles.actionBtn}
                        onClick={() => openDocumentModal(emp.id)}
                      >
                        <FaFileAlt />
                      </Button>
                   <Button
                    variant="outline-success"
                    size="sm"
                    onClick={() => setShowBenefitsModal(true)}
                  >
                    <FaGift className="me-1" />
                  </Button>

                
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </Table>

        <div className="d-flex justify-content-end mt-3">
          <Pagination>
            <Pagination.First 
              onClick={() => setCurrentPage(1)} 
              disabled={currentPage === 1} 
            />
            <Pagination.Prev 
              onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))} 
              disabled={currentPage === 1} 
            />

            {Array.from({ length: Math.ceil(filteredEmployees.length / itemsPerPage) }, (_, i) => (
              <Pagination.Item 
                key={i + 1} 
                active={i + 1 === currentPage} 
                onClick={() => setCurrentPage(i + 1)}
              >
                {i + 1}
              </Pagination.Item>
            ))}

                            <Pagination.Next 
                    onClick={() => setCurrentPage(prev => {
                      const maxPage = Math.max(1, Math.ceil(filteredEmployees.length / itemsPerPage));
                      return Math.min(prev + 1, maxPage);
                    })} 
                    disabled={currentPage >= Math.ceil(filteredEmployees.length / itemsPerPage) || filteredEmployees.length === 0}
                  />

            <Pagination.Last 
              onClick={() => setCurrentPage(Math.ceil(filteredEmployees.length / itemsPerPage))} 
              disabled={currentPage === Math.ceil(filteredEmployees.length / itemsPerPage)} 
            />
          </Pagination>
        </div>
      </div>

      {/* Edit Modal */}
      <Modal show={showEdit} onHide={() => setShowEdit(false)} size="lg">
        <Modal.Header closeButton style={{ backgroundColor: '#800080', color: '#fff' }}>
          <Modal.Title style={{ color: 'white' }}>
            <i className="bi bi-pencil-square me-2"></i> Edit Employee
          </Modal.Title>
        </Modal.Header>

        <Modal.Body>
        <Tabs activeKey={activeTab} onSelect={(k) => setActiveTab(k)} className="mb-3 justify">

           <Tab eventKey="personal"  title={tabLabel(FaUser, "Personal")}>
  <Form>
    <div className="row">
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>First Name</Form.Label>
          <Form.Control 
            name="first_name" 
            value={formData.first_name} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Last Name</Form.Label>
          <Form.Control 
            name="last_name" 
            value={formData.last_name} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
    </div>

    <div className="row mt-2">
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Email</Form.Label>
          <Form.Control 
            name="email" 
            value={formData.email} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Phone</Form.Label>
          <Form.Control 
            name="phone" 
            value={formData.phone} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
    </div>

    <div className="row mt-2">
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Date of Birth</Form.Label>
          <Form.Control 
            type="date" 
            name="dob" 
            value={formData.dob} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Gender</Form.Label>
          <Form.Select 
            name="gender" 
            value={formData.gender} 
            onChange={handleChange}
          >
            <option value="">Select gender</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
            <option>Prefer not to say</option>
          </Form.Select>
        </Form.Group>
      </div>
    </div>

    <Form.Group className="mt-2">
      <Form.Label>Address</Form.Label>
      <Form.Control 
        as="textarea" 
        name="address" 
        value={formData.address} 
        onChange={handleChange} 
      />
    </Form.Group>

    <hr />
    <h4 className="text-primary">
      <i className="bi bi-person-lines-fill" style={{ fontSize: "15px",color: "#3b5998" }}></i> Emergency Contact
    </h4>

    <div className="row">
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Contact Name</Form.Label>
          <Form.Control 
            name="contactName" 
            value={formData.contactName} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Relationship</Form.Label>
          <Form.Control 
            name="relationship" 
            value={formData.relationship} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
    </div>

    <div className="row mt-2">
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Contact Phone</Form.Label>
          <Form.Control 
            name="contactPhone" 
            value={formData.contactPhone} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
      <div className="col-md-6">
        <Form.Group>
          <Form.Label>Contact Email</Form.Label>
          <Form.Control 
            name="contactEmail" 
            value={formData.contactEmail} 
            onChange={handleChange} 
          />
        </Form.Group>
      </div>
    </div>
  </Form>
</Tab>

          <Tab eventKey="employment"  title={tabLabel(FaBriefcase, "Employment")}>
  <div className="p-3">
    <Form>
      {/* Main Employment Section */}
      <Row>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Position</Form.Label>
            <Form.Select name="position" value={formData.position} onChange={handleChange}>
              <option value="">Select position</option>
              {positions.map((pos, index) => (
                <option key={index} value={pos}>{pos}</option>
              ))}
            </Form.Select>
          </Form.Group>
        </Col>

        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Department</Form.Label>
            <Form.Select name="department" value={formData.department} onChange={handleChange}>
              <option value="">Select department</option>
              {departments.map((dept) => (
                <option key={dept.id} value={dept.id}>{dept.department_name}</option>
              ))}
            </Form.Select>
          </Form.Group>
        </Col>

        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Employment Type</Form.Label>
            <Form.Select name="employment_type" value={formData.employment_type} onChange={handleChange}>
              <option value="">Select type</option>
              {enumData?.employmentType?.map((type) => (
                <option key={type} value={type}>{type}</option>
              ))}
            </Form.Select>
          </Form.Group>
        </Col>

        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Hire Date</Form.Label>
            <Form.Control
              type="date"
              name="hire_date"
              value={formData.hire_date}
              onChange={handleChange}
            />
          </Form.Group>
        </Col>

        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Reporting Manager</Form.Label>
            <Form.Select name="reporting_manager" value={formData.reporting_manager} onChange={handleChange}>
              <option value="">Select manager</option>
              {enumData?.reportingManager?.map((manager) => (
                <option key={manager} value={manager}>{manager}</option>
              ))}
            </Form.Select>
          </Form.Group>
        </Col>

        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Work Location</Form.Label>
            <Form.Select name="work_location" value={formData.work_location} onChange={handleChange}>
              <option value="">Select location</option>
              {enumData?.workLocation?.map((loc) => (
                <option key={loc} value={loc}>{loc}</option>
              ))}
            </Form.Select>
          </Form.Group>
        </Col>
      </Row>

      {/* Employment History Title */}
      <hr />
     <div className="d-flex align-items-center mb-3">
  <i className="bi bi-arrow-counterclockwise" style={{ fontSize: "15px", color: "#3b5998" }}></i>
  <h5 className="mb-0 ms-2">Employment History</h5>
</div>


      {/* Dynamic Experience Entries */}
      {employmentHistory.map((exp, index) => (
        <div key={index} className="mb-4 border rounded p-3 bg-light">
          <Row>
            <Col md={12}>
              <Form.Group className="mb-3">
                <Form.Label>Previous Company</Form.Label>
                <Form.Control
                  type="text"
                  name="previous_company"
                  value={exp.previous_company}
                  onChange={(e) => handleExperienceChange(index, 'previous_company', e.target.value)}
                />
              </Form.Group>
            </Col>
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Label>Start Date</Form.Label>
                <Form.Control
                  type="date"
                  name="previous_start_date"
                  value={exp.previous_start_date}
                  onChange={(e) => handleExperienceChange(index, 'previous_start_date', e.target.value)}
                />
              </Form.Group>
            </Col>
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Label>End Date</Form.Label>
                <Form.Control
                  type="date"
                  name="previous_end_date"
                  value={exp.previous_end_date}
                  onChange={(e) => handleExperienceChange(index, 'previous_end_date', e.target.value)}
                />
              </Form.Group>
            </Col>
            <Col md={4}>
              <Form.Group className="mb-3">
                <Form.Label>Position</Form.Label>
                <Form.Control
                  type="text"
                  name="previous_position"
                  value={exp.previous_position}
                  onChange={(e) => handleExperienceChange(index, 'previous_position', e.target.value)}
                />
              </Form.Group>
            </Col>
            <Col md={12}>
              <Form.Group className="mb-3">
                <Form.Label>Responsibilities</Form.Label>
                <Form.Control
                  as="textarea"
                  rows={2}
                  name="previous_responsibilities"
                  value={exp.previous_responsibilities}
                  onChange={(e) => handleExperienceChange(index, 'previous_responsibilities', e.target.value)}
                />
              </Form.Group>
            </Col>
            <div className="text-end">
              <Button variant="danger" size="sm" onClick={() => handleRemoveExperience(index)}>
                Remove
              </Button>
            </div>
          </Row>
        </div>
      ))}

      {/* Add Experience Button */}
      <Button variant="primary" size="sm" onClick={handleAddExperience}>
        + Add Another Position
      </Button>
    </Form>
  </div>
</Tab>


            <Tab eventKey="salary" title={tabLabel(FaMoneyBill, "Salary")}>
  <div className="p-3">
    <Form>
      {/* Base Salary and Pay Frequency */}
      <Row>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Base Salary ($)</Form.Label>
            <Form.Control
              type="text"
              name="base_salary"
              value={formData.base_salary}
              onChange={handleChange}
              placeholder="Enter amount"
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Pay Frequency</Form.Label>
            <Form.Select
              name="pay_frequency"
              value={formData.pay_frequency}
              onChange={handleChange}
            >
              <option value="">Select frequency</option>
              {enumData?.payFrequency?.map((freq) => (
                <option key={freq} value={freq}>{freq}</option>
              ))}
            </Form.Select>
          </Form.Group>
        </Col>
      </Row>

      <hr />
      <h5>
        <div className="d-flex align-items-center mb-3 p-2">
          <i className="bi bi-cash-coin me-2" style={{ fontSize: "16px",color: "#3b5998" }}></i>
          <h5 className="mb-0">Allowances ($)</h5>
        </div>

      </h5>

      <Row>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Housing Allowance ($)</Form.Label>
            <Form.Control
              type="text"
              name="housing_allowance"
              value={formData.housing_allowance || '0'}
              onChange={handleChange}
              placeholder="Enter amount"
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Transport Allowance ($)</Form.Label>
            <Form.Control
              type="text"
              name="transport_allowance"
              value={formData.transport_allowance || ''}
              onChange={handleChange}
              placeholder="Enter amount"
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Medical Allowance ($)</Form.Label>
            <Form.Control
              type="text"
              name="medical_allowance"
              value={formData.medical_allowance || ''}
              onChange={handleChange}
              placeholder="Enter amount"
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Other Allowances ($)</Form.Label>
            <Form.Control
              type="text"
              name="other_allowances"
              value={formData.other_allowances || '0'}
              onChange={handleChange}
              placeholder="Enter amount"
            />
          </Form.Group>
        </Col>
      </Row>

      <hr />
      <h5>
        <i className="bi bi-bank2 me-2" style={{ fontSize: "15px",color: "#3b5998" }}></i>
        Payment Information
      </h5>

      <Row>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Bank Name</Form.Label>
            <Form.Control
              type="text"
              name="bank_name"
              value={formData.bank_name}
              onChange={handleChange}
              placeholder="Enter bank name"
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Account Number</Form.Label>
            <Form.Control
              type="text"
              name="account_number"
              value={formData.account_number}
              onChange={handleChange}
              placeholder="Enter account number"
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Routing Number</Form.Label>
            <Form.Control
              type="text"
              name="routing_number"
              value={formData.routing_number}
              onChange={handleChange}
              placeholder="Enter routing number"
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Payment Method</Form.Label>
            <Form.Control
              type="text"
              name="payment_method"
              value={formData.payment_method}
              onChange={handleChange}
              placeholder="Enter payment method"
            />
          </Form.Group>
        </Col>
      </Row>
    </Form>
  </div>
</Tab>

            <Tab eventKey="documents" title={tabLabel(FaFileAlt, "Documents")}>
  <div className="p-3">
   {[
  { label: "Upload Resume", name: "resume", desc: "PDF, DOC or DOCX (Max 5MB)", icon: "bi bi-file-earmark-arrow-up" },
  { label: "Upload ID Proof", name: "id_proof", desc: "Passport, Driver's License or ID Card (JPG, PNG or PDF)", icon: "bi bi-person-badge" },
  { label: "Employment Contract", name: "employment_contract", desc: "PDF or DOCX (Max 10MB)", icon: "bi bi-file-earmark-text" },
  { label: "Medical Certificate", name: "medical_certificate", desc: "JPG, PNG or PDF (Max 5MB)", icon: "bi bi-heart-pulse" },
  { label: "Education Certificates", name: "education_certificates", desc: "PDF, JPG or PNG (Max 10MB)", icon: "bi bi-mortarboard" },
].map((item, index) => (
  <div
    key={index}
    className="d-flex align-items-center justify-content-between border rounded p-3 mb-3"
    style={{ borderStyle: 'dashed' }}
  >
    <div className="d-flex align-items-start">
      <div
        className="me-3"
        style={{
          backgroundColor: "#3b5998",
          color: "white",
          borderRadius: "50%",
          width: "40px",
          height: "40px",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          fontSize: "18px"
        }}
      >
        <i className={item.icon}></i>
      </div>
      <div>
        <strong>{item.label}</strong>
        <div style={{ fontSize: "0.875rem", color: "#555" }}>{item.desc}</div>
      </div>
    </div>

    <div className="text-end">
      <input
        type="file"
        name={item.name}
        id={`file-${item.name}`}
        onChange={handleFileChange}
        style={{ display: "none" }}
      />
      <Button
        variant="outline-primary"
        size="sm"
        onClick={() => document.getElementById(`file-${item.name}`).click()}
      >
        Browse
      </Button>
    </div>
  </div>
))}


    <Form.Group controlId="documents_authentic">
      <Form.Check
        type="checkbox"
        label="I confirm that all documents provided are authentic and accurate"
        name="documents_authentic"
        checked={formData.documents_authentic || false}
        onChange={handleCheckboxChange}
      />
    </Form.Group>
  </div>
</Tab>

          </Tabs>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowEdit(false)}>Cancel</Button>

          <Button variant="info" onClick={handleNext} style={{ color: 'white' }}>
            Next
          </Button>

          <Button variant="primary" onClick={handleUpdate}>Update</Button>
        </Modal.Footer>

      </Modal>

      {/* Document Modal */}
          <Modal
            show={state.showDocumentModal}
            onHide={() => setState(prev => ({ ...prev, showDocumentModal: false }))}
            size="lg"
            centered
          >
            <Modal.Header
              closeButton
              style={{ backgroundColor: "#17a2b8", padding: "10px 16px" }}
              closeVariant="white"
            >
              <Modal.Title className="w-100 text-white fw-semibold">
                Employee Documents –{" "}
                {state.loading.employees ? (
                  <span className="text-light">Loading...</span>
                ) : (
                  <>
                    {state.selectedEmployee?.first_name} {state.selectedEmployee?.last_name}{" "}
                    ({state.selectedEmployee?.emp_code || `EMP${state.selectedEmployeeId}`})
                  </>
                )}
              </Modal.Title>
            </Modal.Header>

            <Modal.Body>
              {state.loading.documents ? (
                <div className="text-center py-4">
                  <Spinner animation="border" /> Loading documents...
                </div>
              ) : (
                <>
                  <div className="row">
            
              {Object.entries(state.documents || {})
            .filter(([key, value]) =>
              ["resume", "id_proof", "employment_contract", "medical_certificate", "education_certificates"].includes(key) &&
              value // non-null
            )
            .slice(2, 5)
            .map(([key, value], index) => {
              const url = value.startsWith("http")
                ? value
                : `/storage/${value.replace(/^\/+/, '')}`;

              const ext = url.split('.').pop().toLowerCase();
              const isImage = ["jpg", "jpeg", "png"].includes(ext);

              let icon;
              if (ext === "pdf") icon = <FaFilePdf className="text-danger fs-1 mb-2" />;
              else if (["doc", "docx"].includes(ext)) icon = <FaFileWord className="text-primary fs-1 mb-2" />;
              else if (isImage) icon = <FaIdCard className="text-success fs-1 mb-2" />;
              else icon = <FaFileAlt className="text-secondary fs-1 mb-2" />;

              const title = key.replace(/_/g, " ").replace(/\b\w/g, l => l.toUpperCase());

              return (
                <div key={index} className="col-md-4 mb-4 d-flex justify-content-center">
                  <div
                    className="card text-center shadow-sm"
                    style={{
                      width: "100%",
                      maxWidth: "320px",
                      minHeight: "280px",
                      borderRadius: "12px",
                      border: "1px solid #dee2e6",
                    }}
                  >
                    <div className="card-body d-flex flex-column justify-content-between align-items-center">
                      <div className="flex-grow-1 d-flex flex-column align-items-center">
                        {icon}
                        <h5 className="fw-semibold text-center">{title}</h5>

                        {/* 👇 Image inline preview (for image types only) */}
                        {isImage && (
                          <img
                            src={url}
                            alt={title}
                            className="mt-2 rounded shadow-sm"
                            style={{ maxHeight: "120px", maxWidth: "100%", objectFit: "contain" }}
                          />
                        )}
                      </div>
                    <p>
                      Signed date: {state.documents?.signed_date ? new Date(state.documents.signed_date).toLocaleDateString() : 'N/A'}
                    </p>



                      {/* ✅ Preview & Download buttons */}
                      <div className="d-flex justify-content-center gap-2 mt-3">
                        <a href={url} target="_blank" rel="noopener noreferrer">
                          <Button variant="outline-primary" size="sm" className="d-flex align-items-center">
                            <FaEye className="me-1" /> Preview
                          </Button>
                        </a>

                        <a href={url} download>
                          <Button variant="outline-dark" size="sm" className="d-flex align-items-center">
                            <FaDownload className="me-1" /> Download
                          </Button>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}

          </div>

              <div className="d-flex justify-content-center mt-4 flex-wrap gap-3">
                    <Button
                          variant="primary"
                          className="d-flex align-items-center px-3 py-2"
                          onClick={() => {
                            setState(prev => ({ ...prev, showUploadInput: true }));
                          }}
                        >
                          <FaUpload className="me-2" /> Upload New Document
                        </Button>

                     
                    <Button variant="success" className="d-flex align-items-center px-3 py-2">
                      <FaFileAlt className="me-2" /> Generate New Document
                    </Button>
                  </div>
                </>

              )}
              {state.showUploadInput && (
                    <Form className="mt-3">
                      <Form.Group className="mb-3">
                        <Form.Label>Document Title</Form.Label>
                        <Form.Control
                          type="text"
                          name="title"
                          value={state.newDocument.title || ""}
                          onChange={(e) => {
                            const value = e.target.value;
                            setState(prev => ({
                              ...prev,
                              newDocument: {
                                ...prev.newDocument,
                                title: value
                              }
                            }));
                          }}
                          placeholder="Enter title"
                        />
                      </Form.Group>

                      <Form.Group className="mb-3">
                        <Form.Label>Upload File</Form.Label>
                        <Form.Control
                          type="file"
                          onChange={(e) => {
                            const file = e.target.files[0];
                            setState(prev => ({
                              ...prev,
                              newDocument: {
                                ...prev.newDocument,
                                file
                              }
                            }));
                          }}
                        />
                      </Form.Group>

                      <Button
                        variant="success"
                        onClick={uploadDocument}
                        disabled={state.uploading}
                      >
                        {state.uploading ? "Uploading..." : "Submit Document"}
                      </Button>
                    </Form>
                  )}

              
            </Modal.Body>

            <Modal.Footer>
              <Button
                variant="secondary"
                onClick={() => setState(prev => ({ ...prev, showDocumentModal: false }))}
              >
                Close
              </Button>
            </Modal.Footer>
          </Modal>



      {/* Leave Request Modal */}
      <Modal show={state.showLeaveModal} onHide={() => setState(prev => ({...prev, showLeaveModal: false}))}>
        <Modal.Header closeButton>
          <Modal.Title>
            Apply for Leave – <strong>{state.selectedEmployee?.first_name} {state.selectedEmployee?.last_name} (EMP{state.selectedEmployee?.id})</strong>
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form onSubmit={handleSubmitLeave}>
           <Form.Group className="mb-4">
  <Form.Label><strong>Leave Type</strong></Form.Label>
 <Form.Select
  name="leaveType"
  value={state.leaveData.leaveType}
  onChange={handleChange}
>

    <option value="">Select Leave Type</option>
    {leaveTypes.map((type, index) => (
      <option key={index} value={type}>{type}</option>
    ))}
  </Form.Select>
</Form.Group>


            <Row className="mb-3">
              <Col md={6}>
                <Form.Group>
                  <Form.Label><strong>From Date</strong></Form.Label>
                  <div className="d-flex align-items-center">
                    <FiCalendar className="me-2 text-primary" />
                    <Form.Control
                      type="date"
                      name="fromDate"
                      value={state.leaveData.fromDate}
                      onChange={(e) => setState(prev => ({
                        ...prev,
                        leaveData: {
                          ...prev.leaveData,
                          fromDate: e.target.value
                        }
                      }))}
                      required
                    />
                  </div>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group>
                  <Form.Label><strong>To Date</strong></Form.Label>
                  <div className="d-flex align-items-center">
                    <FiCalendar className="me-2 text-primary" />
                    <Form.Control
                      type="date"
                      name="toDate"
                      value={state.leaveData.toDate}
                      onChange={(e) => setState(prev => ({
                        ...prev,
                        leaveData: {
                          ...prev.leaveData,
                          toDate: e.target.value
                        }
                      }))}
                      required
                    />
                  </div>
                </Form.Group>
              </Col>
            </Row>

            <Form.Group className="mb-3">
              <Form.Label><strong>Reason</strong></Form.Label>
              <Form.Control
                as="textarea"
                rows={3}
                name="reason"
                value={state.leaveData.reason}
                onChange={(e) => setState(prev => ({
                  ...prev,
                  leaveData: {
                    ...prev.leaveData,
                    reason: e.target.value
                  }
                }))}
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Check
                type="checkbox"
                label={
                  <>
                    <FiAlertTriangle className="me-2 text-danger" />
                    Emergency Leave
                  </>
                }
                name="isEmergency"
                checked={state.leaveData.isEmergency}
                onChange={(e) => setState(prev => ({
                  ...prev,
                  leaveData: {
                    ...prev.leaveData,
                    isEmergency: e.target.checked
                  }
                }))}
              />
            </Form.Group>

            <Alert variant="info" className="d-flex align-items-center">
              <FiCheckCircle className="me-2" size={20} />
              <strong>AI Approval:</strong> Based on past patterns, 92% chance this leave will be approved.
            </Alert>

            <div className="d-flex justify-content-between mt-4">
              <Button 
                variant="outline-secondary" 
                onClick={() => setState(prev => ({...prev, showLeaveModal: false}))}
              >
                Cancel
              </Button>
              <div>
                <Button variant="primary" type="submit" className="me-2">
                  Submit Request
                </Button>
                <Button 
                  variant="success" 
                  onClick={handleAutoApprove}
                >
                  Auto-Approve
                </Button>
              </div>
            </div>
          </Form>
        </Modal.Body>
      </Modal>

      {/* Message Modal */}
      <Modal
        show={state.showMessageModal}
        onHide={() => setState(prev => ({ ...prev, showMessageModal: false }))}
      >
        <Modal.Header closeButton className="bg-primary">
          <Modal.Title className="d-flex align-items-center gap-2">
            <FaEnvelope />
            <span>
              Send Message to{" "}
              <strong>
                {state.selectedEmployee?.first_name} {state.selectedEmployee?.last_name}{" "}
                ({state.selectedEmployee?.emp_code || `EMP${state.selectedEmployee?.id}`})
              </strong>
            </span>
          </Modal.Title>
        </Modal.Header>

        <Modal.Body>
          <Form onSubmit={handleSubmitMessage}>
            <Form.Group className="mb-3">
              <Form.Label><strong>Subject</strong></Form.Label>
              <Form.Control
                type="text"
                name="subject"
                value={state.messageData.subject}
                onChange={(e) =>
                  setState(prev => ({
                    ...prev,
                    messageData: {
                      ...prev.messageData,
                      subject: e.target.value
                    }
                  }))
                }
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label><strong>Message</strong></Form.Label>
              <Form.Control
                as="textarea"
                rows={4}
                name="content"
                value={state.messageData.content}
                onChange={(e) =>
                  setState(prev => ({
                    ...prev,
                    messageData: {
                      ...prev.messageData,
                      content: e.target.value
                    }
                  }))
                }
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
            <Form.Label><strong>Priority</strong></Form.Label>
            <Form.Select
              name="priority"
              value={state.messageData.priority}
              onChange={handleChange}
              required
            >
              <option value="">Select Priority</option>
              {priorities.map((p, index) => (
                <option key={index} value={p.value}>{p.label}</option>
              ))}
            </Form.Select>
          </Form.Group>

          </Form>
        </Modal.Body>

        <Modal.Footer>
          <Button
            variant="secondary"
            onClick={() => setState(prev => ({ ...prev, showMessageModal: false }))}
          >
            Cancel
          </Button>
          <Button
            variant="primary"
            onClick={handleSubmitMessage}
          >
            Send Message
          </Button>
        </Modal.Footer>
      </Modal>

      {/* Payroll Modal */}
     <Modal
      show={state.showPayrollModal}
      onHide={() => setState(prev => ({ ...prev, showPayrollModal: false }))}
      size="lg"
      centered
    >
      <Modal.Header
        closeButton
        style={{ backgroundColor: "#28a745", color: "white", padding: "12px 16px" }}
        closeVariant="white"
      >
        <Modal.Title className="w-100" style={{ fontSize: "18px", fontWeight: "600", color: "white" }}>
          Payroll Details –{" "}
          {state.selectedEmployee?.first_name || ''} {state.selectedEmployee?.last_name || ''} 
          ({state.selectedEmployee?.emp_code || (state.selectedEmployee?.id ? `EMP${state.selectedEmployee.id}` : '')}) –{" "}
          {new Date(state.payrollMonth || new Date()).toLocaleString('default', { month: 'long', year: 'numeric' })}
        </Modal.Title>
      </Modal.Header>

      <Modal.Body>
        {/* 🖨️ Printable Section */}
        <div ref={slipRef} style={{ maxWidth: "900px", margin: "0 auto", padding: "30px", paddingTop: "10px" }}>
          <div className="d-flex gap-3 mb-4 flex-wrap">
            {/* Earnings */}
            <div className="flex-grow-1 p-3 bg-white rounded shadow-sm" style={{ minWidth: "300px", border: "1px solid #ddd" }}>
              <div className="bg-light p-2 rounded mb-3">
                <h2 className="h5 mb-0">Earnings</h2>
              </div>
              <table className="w-100">
                <tbody>
                  <tr><td className="py-1">Basic Salary</td><td className="text-end py-1">₹{state.payrollDetails?.basic_salary?.toLocaleString() || "0.00"}</td></tr>
                  <tr><td className="py-1">House Rent Allowance</td><td className="text-end py-1">₹{state.payrollDetails?.hra?.toLocaleString() || "0.00"}</td></tr>
                  <tr><td className="py-1">Transport Allowance</td><td className="text-end py-1">₹{state.payrollDetails?.transport_allowance?.toLocaleString() || "0.00"}</td></tr>
                  <tr className="bg-light fw-bold"><td className="py-2">Total Earnings</td><td className="text-end py-2">₹{state.payrollDetails?.total_earnings?.toLocaleString() || "0.00"}</td></tr>
                </tbody>
              </table>
            </div>

            {/* Deductions */}
            <div className="flex-grow-1 p-3 bg-white rounded shadow-sm" style={{ minWidth: "300px", border: "1px solid #ddd" }}>
              <div className="bg-light p-2 rounded mb-3">
                <h2 className="h5 mb-0">Deductions</h2>
              </div>
              <table className="w-100">
                <tbody>
                  <tr><td className="py-1">Professional Tax</td><td className="text-end py-1">₹{state.payrollDetails?.professional_tax?.toLocaleString() || "0.00"}</td></tr>
                  <tr><td className="py-1">TDS</td><td className="text-end py-1">₹{state.payrollDetails?.tds?.toLocaleString() || "0.00"}</td></tr>
                  <tr><td className="py-1">Loan Recovery</td><td className="text-end py-1">₹{state.payrollDetails?.loan_recovery?.toLocaleString() || "0.00"}</td></tr>
                  <tr className="bg-light fw-bold"><td className="py-2">Total Deductions</td><td className="text-end py-2">₹{state.payrollDetails?.total_deductions?.toLocaleString() || "0.00"}</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          {/* Net Pay */}
          <div className="text-center py-3">
            <h2 className="mb-0 fw-bold" style={{ fontSize: "24px", color: "#000" }}>
              Net Pay: ₹{state.payrollDetails?.net_pay?.toLocaleString() || "0.00"}
            </h2>
          </div>

          {/* AI Message */}
       
</div>

        {/* 🚫 Not included in print: Action buttons */}
        <div
  className="no-print"
  style={{ padding: "16px"}}
>
  <div className="d-flex justify-content-between align-items-center">
    <Button
      variant="secondary"
      onClick={() => setState(prev => ({ ...prev, showPayrollModal: false }))}
    >
      Close
    </Button>
    <div className="d-flex gap-3">
      <Button onClick={handlePrintSlip} style={{ backgroundColor: "#007bff", borderColor: "#007bff" }}>
        Print Slip
      </Button>
      <Button style={{ backgroundColor: "#28a745", borderColor: "#28a745" }}>
        Process Payment
      </Button>
    </div>
  </div>
</div>

      </Modal.Body>
    </Modal>
    

         {/* Benefit Modal */}

           <Modal
              show={showBenefitsModal}
              onHide={() => setShowBenefitsModal(false)}
              size="lg"
              centered
              scrollable
              dialogClassName="custom-rectangular-modal"
              backdropClassName="light-modal-backdrop" // Add this
            >
        <Modal.Header closeButton onHide={handleClose} className="border-0 pb-0" />

        {/* Header with Date */}
        <div className="px-4 pt-4 d-flex justify-content-end">
          <div className="d-inline-flex align-items-center bg-white p-2 rounded-3 text-secondary">
            <FaCalendarAlt className="text-primary me-2" size={12} />
            {new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' })}
          </div>
        </div>

        {/* Summary */}
        <div className="px-4 pt-3">
          <Card className="shadow-sm" style={{ borderRadius: '15px', padding: '1.5rem', backgroundColor: '#f9f9f9' }}>
            <Row className="text-center">
              <Col md={3}><strong style={{ fontSize: '1.5rem', color: '#28a745' }}>₹852,500</strong><p className="text-muted">Total Benefits Value</p></Col>
              <Col md={3}><strong>9</strong><p className="text-muted">Active Benefits</p></Col>
              <Col md={3}><strong>2025-12-31</strong><p className="text-muted">Next Renewal</p></Col>
              <Col md={3}><strong>4.2/5</strong><p className="text-muted">Satisfaction</p></Col>
            </Row>
          </Card>
        </div>

        {/* Benefits Grid */}
        <Modal.Body className="px-4 py-3">
          <Row className="g-3">
            {benefitItems.map(item => (
              <Col md={3} key={item.id}>
                <Card className="benefit-card shadow-sm h-100" style={{ borderRadius: '12px' }}>
                  <Card.Body>
                    {/* Top row with icon + label + edit */}
                    <div className="d-flex justify-content-between align-items-center mb-2">
                      <div
                        className="d-flex align-items-center gap-2 cursor-pointer"
                        onClick={() => {
                          setViewItem(viewItem === item.id ? null : item.id);
                          setActiveItem(null);
                        }}
                      >
                        <div className="d-flex justify-content-center align-items-center" style={{ backgroundColor: '#eaf0f6', borderRadius: '50%', width: '30px', height: '30px', color: '#3a5ea8' }}>
                          {item.icon}
                        </div>
                        <span className="fw-semibold">{item.label}</span>
                      </div>
                      <FaEdit
                        title="Edit"
                        className="text-muted"
                        style={{ cursor: 'pointer' }}
                        onClick={() => {
                          setActiveItem(item.id);
                          setViewItem(null);
                        }}
                      />
                    </div>

                    <hr className="my-2" />

                    {/* View Mode */}
                    {viewItem === item.id && (
                      <div style={{ fontSize: '0.85rem', color: '#6c757d' }}>
                        <p className="mb-1">View content for <strong>{item.label}</strong>...</p>
                      </div>
                    )}

                    {/* Edit Mode */}
                    {activeItem === item.id && (
                      <Form onSubmit={handleSubmit}>
                        <Form.Group className="mb-2">
                          <Form.Label>{item.label} Detail</Form.Label>
                          <Form.Control
                            size="sm"
                            type="text"
                            name={item.id}
                            value={formData[item.id] || ''}
                            onChange={handleInputChange}
                          />
                        </Form.Group>
                        <Button type="submit" size="sm" variant="primary">
                          <FaSave className="me-1" /> Save
                        </Button>
                      </Form>
                    )}

                    {/* Placeholder when nothing is open */}
                    {!viewItem && activeItem !== item.id && (
                      <div style={{ fontSize: '0.85rem', color: '#6c757d' }}>Click to view details</div>
                    )}
                  </Card.Body>
                </Card>
              </Col>
            ))}
          </Row>
        </Modal.Body>

        {/* Footer */}
        <Modal.Footer className="d-flex justify-content-end gap-2">
          <Button variant="secondary" onClick={handleClose} className="d-flex align-items-center">
            <FaTimes className="me-1" /> Close
          </Button>
          <Button variant="secondary" className="d-flex align-items-center">
            <FaFilePdf className="me-1" /> Export to PDF
          </Button>
          <Button variant="primary" className="d-flex align-items-center">
            <FaSave className="me-1" /> Save All Changes
          </Button>
        </Modal.Footer>
      </Modal>

    </div>
  );
});

export default Tables;