import React, { useState, useEffect, useContext } from 'react';
import { useParams, useNavigate, useLocation } from 'react-router-dom';
import NewProjectModal from './NewProjectModal';
import InvoiceModal from './InvoiceModal';
import {
  Container,
  Row,
  Col,
  Button,
  Modal,
  Tabs,
  Tab,
  Accordion,
  AccordionContext,
  useAccordionButton,
  Card,
  Form
} from 'react-bootstrap';
import {
  FaUser,
  FaUsers,
  FaUserTie,
  FaUserFriends,
  FaEnvelope,
  FaFileInvoice,
  FaProjectDiagram,
  FaMoneyBillWave,
  FaClipboardList,
  FaBriefcase,
  FaPlus,
  FaTrash,
  FaPhone,
  FaDollarSign,
  FaCertificate,
  FaFileAlt,
  FaBuilding
} from 'react-icons/fa';
import { BsChevronRight, BsChevronDown } from "react-icons/bs";
import axios from 'axios';

// Custom components
import StaffDetails from './StaffDetails';
import ContactForm from './ContactForm';
import FinancialHR from './FinancialHr';
import Skills from './Skills';
import Dashboard from './Dashboard';
import StaffTable from './StaffTable';
import CustomerProfileForm from './CustomerProfileForm';
import { toast } from 'react-toastify';
// Styles
import './styles.css';
import './page.css';
import CustomerProfiles from './CustomerProfiles';

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

export default function Customerdetail() {

 const { id } = useParams();
  const navigate = useNavigate();
  const location = useLocation();
  const [showNewProject, setShowNewProject] = useState(false);
  // State setup
  const [customer, setCustomer] = useState({});
  const [gstDetails, setGstDetails] = useState([]);
  const [loginEnabled, setLoginEnabled] = useState(false);
const [organization, setOrganization] = useState('');
const [username, setUsername] = useState('');
const [password, setPassword] = useState('');
  const [addresses, setAddresses] = useState([]);
  const [contactPersons, setContactPersons] = useState([]);
  const [newAddress, setNewAddress] = useState({ address: '', city: '', state: '', pincode: '' });
  const [showForm, setShowForm] = useState(false);
   const [showModal, setShowModal] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [activeKey, setActiveKey] = useState('profile');
  const [showEstimateModal, setShowEstimateModal] = useState(false);
  const [estimateNumber, setEstimateNumber] = useState('');
const [estimateDate, setEstimateDate] = useState('');
const [amount, setAmount] = useState('');
const [status, setStatus] = useState('Pending');
const handleCloseEstimate = () => setShowEstimateModal(false);

const quickActions = [
  { label: 'New Project', icon: <FaPlus />, onClick: () => setShowNewProject(true) },
  { label: 'New Invoice', icon: <FaFileInvoice />, onClick: () => setShowModal(true) },
  { label: 'New Estimate', icon: <FaClipboardList />, onClick: () => setShowEstimateModal(true) },
  { label: 'Log Payment', icon: <FaMoneyBillWave /> },
  { label: 'Send Mail', icon: <FaEnvelope /> }
];
useEffect(() => {
  if (showEstimateModal) {
    const randomEstimateNumber = `EST-${Math.floor(1000 + Math.random() * 9000)}`;
    setEstimateNumber(randomEstimateNumber);
    setEstimateDate(new Date().toISOString().split('T')[0]); // yyyy-mm-dd
  }
}, [showEstimateModal]);



  // Fetch customer data
  useEffect(() => {
    if (id) {
      axios.get(`http://127.0.0.1:8000/api/v1/customers/customer/${id}`)
        .then((res) => {
          const data = res.data;
          setCustomer(data);
          setGstDetails(data.gst_details || []);
          setAddresses(data.shipping_addresses || []);
          setContactPersons(data.contact_persons || []);
        })
        .catch((err) => {
          console.error('Failed to fetch customer:', err);
          toast.error('Failed to load customer data');
        });
    }
  }, [id]);

  // Update field in customer object
  const handleCustomerUpdate = (updatedCustomer) => {
    setCustomer(updatedCustomer);
  };

  // GST update
  const handleGstChange = (index, field, value) => {
    const updated = [...gstDetails];
    updated[index] = { ...updated[index], [field]: value };
    setGstDetails(updated);
  };

  // Address update
  const handleAddressEdit = (index, field, value) => {
    const updated = [...addresses];
    updated[index] = { ...updated[index], [field]: value };
    setAddresses(updated);
  };
const handleCreateEstimate = async () => {
  const payload = {
    customer_id: customer?.id,
    estimate_number: estimateNumber,
    date: estimateDate,
    amount,
    status
  };

  try {
    await axios.post('http://127.0.0.1:8000/api/v1/estimate/estimates', payload);
    toast.success("Estimate created successfully!");
    setShowEstimateModal(false);
  } catch (error) {
    console.error(error);
    toast.error("Failed to create estimate.");
  }
};

  // Contact person update
  const handleContactChange = (index, field, value) => {
    const updated = [...contactPersons];
    updated[index] = { ...updated[index], [field]: value };
    setContactPersons(updated);
  };

  // Add new address
  const handleAddNewAddress = () => {
    if (newAddress.address && newAddress.city && newAddress.state && newAddress.pincode) {
      setAddresses([...addresses, newAddress]);
      setNewAddress({ address: '', city: '', state: '', pincode: '' });
      setShowForm(false);
    } else {
      toast.error('Please fill all address fields');
    }
  };

  // Update customer to backend
  const handleUpdateCustomer = async () => {
    try {
      const payload = {
        ...customer,
        gst_details: gstDetails,
        shipping_addresses: addresses,
        contact_persons: contactPersons
      };

      await axios.put(`http://127.0.0.1:8000/api/customer/customer/${id}`, payload);
      toast.success('Customer updated successfully!');
      setShowEditModal(false);
    } catch (err) {
      console.error('Failed to update customer:', err);
      toast.error('Failed to update customer');
    }
  };
  const handleDeleteCustomer = async (id) => {
  try {
    await axios.delete(`http://127.0.0.1:8000/api/customer/customer/${id}`);
    toast.success('Customer deleted successfully!');
    // Optionally: redirect or refresh list
    navigate('/customer'); // or any appropriate path
  } catch (error) {
    console.error('Delete failed:', error);
    toast.error('Failed to delete customer');
  }
};

  if (!customer) return <p>Loading...</p>;
  return (
    <div className="bg-light min-vh-100">
      
      <Container className="py-4">
        {/* Back Button */}
        <div className="mb-3">
          <Button variant="link" onClick={() => navigate(-1)} className="p-0 text-primary">
            ← Back to Customers
          </Button>
        </div>

        {/* Header Section */}
        <div className="d-flex justify-content-between align-items-start mb-4">
          <div className="d-flex align-items-center gap-3">
            <div
              className="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow"
              style={{ width: 80, height: 80, fontSize: '2rem' }}
            >
              {customer.company_name?.charAt(0) || 'C'}
            </div>
            <div>
              <h3 className="fw-bold mb-1">{customer.company_name}</h3>
              <div className="text-muted">{customer.organization}</div>
             <div className="small text-muted">
  Last login: {customer?.created_at ? customer.created_at.slice(0, 10) : 'Never'}
</div>

            </div>
          </div>
          <div className="d-flex gap-2">
            <Button variant="outline-primary" onClick={() => setShowEditModal(true)}>
              <i className="bi bi-pencil-square"></i> Edit
            </Button>
            <Button variant="outline-danger" onClick={() => handleDeleteCustomer(customer.id)}>
              <i className="bi bi-trash"></i> Delete
            </Button>

          </div>
        </div>

        {/* Quick Actions */}
        <div className="bg-white p-3 rounded shadow-sm mb-4">
          <h6 className="text-muted mb-3">Quick Actions</h6>
          <div className="d-flex flex-wrap gap-3">
            {quickActions.map((action, idx) => (
              <Button key={idx} variant="light" className="border text-nowrap d-flex align-items-center gap-2" onClick={action.onClick}>
                {action.icon} {action.label}
              </Button>
            ))}
             <NewProjectModal show={showNewProject} onHide={() => setShowNewProject(false)} customer={customer.company_name} id={id} />
                <InvoiceModal
              show={showModal}
              onClose={() => setShowModal(false)}
              customerId={id}/>
          </div>
        </div>

        {/* Detail Tabs */}
        <div className="bg-white p-3 rounded shadow-sm">
          <Tabs activeKey={activeKey} onSelect={(k) => setActiveKey(k)} className="mb-3">
            <Tab
              eventKey="profile"
              title={
                <div className="d-inline-flex align-items-center">
                  <FaUser className="me-2" />
                  <span>Profile</span>
                </div>
              }
            >
              {/* Company & Contact Info */}
              <div className="mb-4">
                <h5 className="mb-3">Company & Contact Information</h5>
                <hr/>
                <div className="row mb-2">
                  <div className="col-md-4">
                    <strong>Organization:</strong>
                    <div>{customer.organization || 'N/A'}</div>
                  </div>
                  <div className="col-md-4">
                    <strong>Customer Type:</strong>
                    <div>{customer.customer_type || 'N/A'}</div>
                  </div>
                  <div className="col-md-4">
                    <strong>Company Name:</strong>
                    <div>{customer.company_name || 'N/A'}</div>
                  </div>
                </div>

                <div className="row mb-2">
                  <div className="col-md-4">
                    <strong>Display Name:</strong>
                    <div>{customer.display_name || 'N/A'}</div>
                  </div>
                  <div className="col-md-4">
                    <strong>Owner Name:</strong>
                    <div>{customer.owner_name || 'N/A'}</div>
                  </div>
                  <div className="col-md-4">
                    <strong>PAN No.:</strong>
                    <div>{customer.pan_no || 'N/A'}</div>
                  </div>
                </div> 
                <hr/>

                <div className="mt-3">
                  <h5>Registered Address:</h5>
                  <hr/>
                  <div>{customer.address || 'No registered address'}</div>
                </div>
              </div>
              <hr/>
              
              {/* GST Details Table */}
              <div className="mb-4">
                <h5>GST Details</h5>
                <hr/>
                <div className="table-responsive">
                  <table className="table table-hover table-bordered align-middle">
                    <thead className="table-light">
                      <tr>
                        <th>GST Number</th>
                        <th>Place of Supply</th>
                        <th>Registered Address</th>
                      </tr>
                    </thead>
                    <tbody>
                      {gstDetails.length > 0 ? (
                        gstDetails.map((gst, index) => (
                          <tr key={index}>
                            <td>{gst.gst_number || 'N/A'}</td>
                            <td>{gst.place_of_supply || 'N/A'}</td>
                            <td>{gst.registered_address || 'N/A'}</td>
                          </tr>
                        ))
                      ) : (
                        <tr>
                          <td colSpan="3" className="text-center">No GST details available</td>
                        </tr>
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
              <hr/>
              
              {/* Shipping Address Table */}
              <div>
                <h5>Shipping Addresses</h5>
                <hr/>
                <div className="table-responsive">
                  <table className="table table-hover table-bordered align-middle">
                    <thead className="table-light">
                      <tr>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Pincode</th>
                      </tr>
                    </thead>
                    <tbody>
                      {addresses.length > 0 ? (
                        addresses.map((address, index) => (
                          <tr key={index}>
                            <td>{address.address || 'N/A'}</td>
                            <td>{address.city || 'N/A'}</td>
                            <td>{address.state || 'N/A'}</td>
                            <td>{address.pincode || 'N/A'}</td>
                          </tr>
                        ))
                      ) : (
                        <tr>
                          <td colSpan="4" className="text-center">No shipping addresses available</td>
                        </tr>
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            </Tab>

            <Tab
              eventKey="contacts"
              title={
                <div className="d-inline-flex align-items-center">
                  <FaEnvelope className="me-2" />
                  <span>Contacts</span>
                </div>
              }
            >
              <div className="pt-3 px-2">
                <h6 className="fw-semibold mb-2">All Contacts</h6>
                <hr className="my-2" />
                {contactPersons.length > 0 ? (
                  <div className="table-responsive">
                    <table className="table table-hover table-bordered align-middle">
                      <thead className="table-light">
                        <tr>
                          <th>Name</th>
                          <th>Designation</th>
                          <th>Work Email</th>
                          <th>Work Phone</th>
                        </tr>
                      </thead>
                      <tbody>
                        {contactPersons.map((person, index) => (
                          <tr key={index}>
                            <td>{person.contact_name || 'N/A'}</td>
                            <td>{person.designation || 'N/A'}</td>
                            <td>{person.work_email || 'N/A'}</td>
                            <td>{person.work_phone || 'N/A'}</td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                ) : (
                  <p className="text-muted mb-0">No contacts found.</p>
                )}
              </div>
            </Tab>

            <Tab
              eventKey="projects"
              title={
                <div className="d-inline-flex align-items-center">
                  <FaProjectDiagram className="me-2" />
                  <span>Projects</span>
                </div>
              }
            >
              <div className="pt-3 px-2">
  <h6 className="fw-semibold mb-3">Projects</h6>
  <div className="table-responsive">
    <table className="table table-hover table-bordered align-middle">
      <thead className="table-light">
        <tr>
          <th>Project Name</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Value</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        {customer?.projects?.length > 0 ? (
          customer.projects.map((project) => (
            <tr key={project.id}>
              <td>{project.project_name}</td>
              <td>{new Date(project.project_starting_date).toLocaleDateString()}</td>
              <td>{new Date(project.expected_release_date).toLocaleDateString()}</td>
              <td>{project.amount ?? 'N/A'}</td>
              <td>{project.status === 1 ? 'Active' : 'Inactive'}</td>
            </tr>
          ))
        ) : (
          <tr>
            <td colSpan="5" className="text-center">No projects found</td>
          </tr>
        )}
      </tbody>
    </table>
  </div>
</div>

            </Tab>

            <Tab
              eventKey="invoices"
              title={
                <span className="d-inline-flex align-items-center">
                  <FaFileInvoice className="me-2" />
                  Invoices
                </span>
              }
            >
              <div className="pt-3 px-2">
                <h6 className="fw-semibold mb-3">Invoices</h6>
                <div className="table-responsive">
                  <table className="table table-hover table-bordered align-middle">
                    <thead className="table-light">
                      <tr>
                        <th>Invoice ID</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      {customer?.invoices && customer?.invoices.length > 0 ? (
                        customer ?.invoices.map((invoice, index) => (
                          <tr key={index}>
                            <td>{invoice.id}</td>
                            <td>{new Date(invoice.issue_date).toLocaleDateString()}</td>
                            <td>{new Date(invoice.due_date).toLocaleDateString()}</td>
                            <td>{invoice.amount}</td>
                            <td>{invoice.status}</td>
                          </tr>
                        ))
                      ) : (
                        <tr>
                          <td colSpan="5" className="text-center">No invoices found</td>
                        </tr>
                      )}
                    </tbody>

                  </table>
                </div>
              </div>
            </Tab>

            <Tab
              eventKey="estimates"
              title={
                <span className="d-inline-flex align-items-center">
                  <FaClipboardList className="me-2" />
                  Estimates
                </span>
              }
            >
              <div className="pt-3 px-2">
                <h6 className="fw-semibold mb-3">Estimates</h6>
                <div className="table-responsive">
                  <table className="table table-hover table-bordered align-middle">
                    <thead className="table-light">
                      <tr>
                        <th>Estimate ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
  {customer?.estimates?.length > 0 ? (
    customer.estimates.map((estimate, idx) => (
      <tr key={idx}>
        <td>{estimate.estimate_number || `EST-${estimate.id}`}</td>
        <td>{estimate.date}</td>
        <td>₹ {parseFloat(estimate.amount).toFixed(2)}</td>
        <td>
          <span
            className={`badge rounded-pill ${
              estimate.status === 'Approved'
                ? 'bg-success'
                : estimate.status === 'Rejected'
                ? 'bg-danger'
                : 'bg-warning text-dark'
            }`}
          >
            {estimate.status}
          </span>
        </td>
      </tr>
    ))
  ) : (
    <tr>
      <td colSpan="4" className="text-center text-muted">
        No estimates found.
      </td>
    </tr>
  )}
</tbody>

                  </table>
                </div>
              </div>
            </Tab>

            <Tab eventKey="payments" title={<span> <div className="d-inline-flex align-items-center"><FaMoneyBillWave className="me-1" />Payments</div></span>}>
              <p>Payment history for this customer.</p>
            </Tab>

            <Tab eventKey="mails" title={<span> <div className="d-inline-flex align-items-center"><FaEnvelope className="me-1" />Mails</div></span>}>
              <p>Emails sent to this customer.</p>
            </Tab>
          </Tabs>
        </div>
       
        {/* Edit Customer Modal */}
        <Modal show={showEditModal} onHide={() => setShowEditModal(false)} size="lg" centered scrollable>
          <Modal.Header closeButton>
            <Modal.Title className="fw-bold">Edit Customer</Modal.Title>
          </Modal.Header>
          <hr />

          {/* Login & Organization Section */}
          <Card className="mb-4 p-3 shadow-sm border rounded bg-light" style={{ maxWidth: '720px', marginLeft: '40px' }}>
            <Row className="mb-3">
              <Col md={6} className="d-flex align-items-center">
                <Form.Label className="fw-semibold mb-0 me-3">Login is enabled</Form.Label>
                <Form.Check
                  type="switch"
                  id="login-switch"
                  checked={loginEnabled}
                  onChange={(e) => setLoginEnabled(e.target.checked)}
                />
              </Col>

              <Col md={6}>
                <Form.Label className="fw-semibold">Organization <span className="text-danger">*</span></Form.Label>
                <Form.Select value={organization} onChange={(e) => setOrganization(e.target.value)}>
                  <option value="">Select Organization</option>
                  <option>Bangalore Organization</option>
                  <option>Kochi Organization</option>
                  <option>Calicut Organization</option>
                </Form.Select>
              </Col>
            </Row>

            <Row>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label className="fw-semibold">Username <span className="text-danger">*</span></Form.Label>
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
                  <Form.Label className="fw-semibold">Password <span className="text-danger">*</span></Form.Label>
                  <Form.Control
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    placeholder="Enter password"
                    disabled={!loginEnabled}
                  />
                </Form.Group>
              </Col>
            </Row>
          </Card>

          {/* Modal Body */}
        <Modal.Body>
        <Accordion defaultActiveKey="0" alwaysOpen>
          {/* Primary Details */}
          <Card>
            <CustomAccordionToggle eventKey="0" icon={<FaUserTie />}>
              Primary Details
            </CustomAccordionToggle>
            <Accordion.Collapse eventKey="0">
              <Card.Body>
                <CustomerProfiles
                  customer={customer}
                  setCustomer={setCustomer}
                />
              </Card.Body>
            </Accordion.Collapse>
          </Card>

          {/* GST Details */}
          <Card>
            <CustomAccordionToggle eventKey="1" icon={<FaFileInvoice />}>
              GST Details
            </CustomAccordionToggle>
            <Accordion.Collapse eventKey="1">
              <Card.Body>
                {gstDetails.map((gst, index) => (
                  <div key={index} className="mb-3 p-3 border rounded">
                    <Row>
                      <Col md={6}>
                        <Form.Group>
                          <Form.Label>GST No.</Form.Label>
                          <Form.Control
                            value={gst.gst_number}
                            onChange={(e) =>
                              handleGstChange(index, 'gst_number', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                      <Col md={6}>
                        <Form.Group>
                          <Form.Label>Place of Supply</Form.Label>
                          <Form.Control
                            value={gst.place_of_supply}
                            onChange={(e) =>
                              handleGstChange(index, 'place_of_supply', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                    </Row>
                    <Button
                      variant="danger"
                      size="sm"
                      onClick={() => {
                        const updated = [...gstDetails];
                        updated.splice(index, 1);
                        setGstDetails(updated);
                      }}
                    >
                      <FaTrash /> 
                    </Button>
                  </div>
                ))}
                <Button
                  onClick={() =>
                    setGstDetails([...gstDetails, { gst_number: '', place_of_supply: '' }])
                  }
                >
                  + Add GST
                </Button>
              </Card.Body>
            </Accordion.Collapse>
          </Card>

          {/* Shipping Addresses */}
          <Card>
            <CustomAccordionToggle eventKey="2" icon={<FaClipboardList />}>
              Shipping Addresses
            </CustomAccordionToggle>
            <Accordion.Collapse eventKey="2">
              <Card.Body>
                {addresses.map((address, index) => (
                  <div key={index} className="mb-3 p-3 border rounded">
                    <Row>
                      <Col md={12}>
                        <Form.Group>
                          <Form.Label>Address</Form.Label>
                          <Form.Control
                            value={address.address}
                            onChange={(e) =>
                              handleAddressEdit(index, 'address', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                    </Row>
                    <Row>
                      <Col md={4}>
                        <Form.Group>
                          <Form.Label>City</Form.Label>
                          <Form.Control
                            value={address.city}
                            onChange={(e) =>
                              handleAddressEdit(index, 'city', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                      <Col md={4}>
                        <Form.Group>
                          <Form.Label>State</Form.Label>
                          <Form.Control
                            value={address.state}
                            onChange={(e) =>
                              handleAddressEdit(index, 'state', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                      <Col md={4}>
                        <Form.Group>
                          <Form.Label>Pincode</Form.Label>
                          <Form.Control
                            value={address.pincode}
                            onChange={(e) =>
                              handleAddressEdit(index, 'pincode', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                    </Row>
                    <Button
                      variant="danger"
                      size="sm"
                      onClick={() => {
                        const updated = [...addresses];
                        updated.splice(index, 1);
                        setAddresses(updated);
                      }}
                    >
                      <FaTrash /> 
                    </Button>
                  </div>
                ))}

                {showForm ? (
                  <div className="mb-3 p-3 border rounded">
                    <Row>
                      <Col md={12}>
                        <Form.Group>
                          <Form.Label>Address</Form.Label>
                          <Form.Control
                            value={newAddress.address}
                            onChange={(e) =>
                              setNewAddress({ ...newAddress, address: e.target.value })
                            }
                          />
                        </Form.Group>
                      </Col>
                    </Row>
                    <Row>
                      <Col md={4}>
                        <Form.Group>
                          <Form.Label>City</Form.Label>
                          <Form.Control
                            value={newAddress.city}
                            onChange={(e) =>
                              setNewAddress({ ...newAddress, city: e.target.value })
                            }
                          />
                        </Form.Group>
                      </Col>
                      <Col md={4}>
                        <Form.Group>
                          <Form.Label>State</Form.Label>
                          <Form.Control
                            value={newAddress.state}
                            onChange={(e) =>
                              setNewAddress({ ...newAddress, state: e.target.value })
                            }
                          />
                        </Form.Group>
                      </Col>
                      <Col md={4}>
                        <Form.Group>
                          <Form.Label>Pincode</Form.Label>
                          <Form.Control
                            value={newAddress.pincode}
                            onChange={(e) =>
                              setNewAddress({ ...newAddress, pincode: e.target.value })
                            }
                          />
                        </Form.Group>
                      </Col>
                    </Row>
                    <Button variant="success" onClick={handleAddNewAddress}>
                      Save Address
                    </Button>
                    <Button variant="link" onClick={() => setShowForm(false)}>
                      Cancel
                    </Button>
                  </div>
                ) : (
                  <Button onClick={() => setShowForm(true)}>+ Add Address</Button>
                )}
              </Card.Body>
            </Accordion.Collapse>
          </Card>

          {/* Contact Persons */}
          <Card>
            <CustomAccordionToggle eventKey="3" icon={<FaUserFriends />}>
              Contact Persons
            </CustomAccordionToggle>
            <Accordion.Collapse eventKey="3">
              <Card.Body>
                {contactPersons.map((person, index) => (
                  <div key={index} className="mb-3 p-3 border rounded">
                    <Row>
                      <Col md={6}>
                        <Form.Group>
                          <Form.Label>Name</Form.Label>
                          <Form.Control
                            value={person.contact_name}
                            onChange={(e) =>
                              handleContactChange(index, 'contact_name', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                      <Col md={6}>
                        <Form.Group>
                          <Form.Label>Designation</Form.Label>
                          <Form.Control
                            value={person.designation}
                            onChange={(e) =>
                              handleContactChange(index, 'designation', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                    </Row>
                    <Row>
                      <Col md={6}>
                        <Form.Group>
                          <Form.Label>Work Email</Form.Label>
                          <Form.Control
                            type="email"
                            value={person.work_email}
                            onChange={(e) =>
                              handleContactChange(index, 'work_email', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                      <Col md={6}>
                        <Form.Group>
                          <Form.Label>Work Phone</Form.Label>
                          <Form.Control
                            value={person.work_phone}
                            onChange={(e) =>
                              handleContactChange(index, 'work_phone', e.target.value)
                            }
                          />
                        </Form.Group>
                      </Col>
                    </Row>
                    <Button
                      variant="danger"
                      size="sm"
                      onClick={() => {
                        const updated = [...contactPersons];
                        updated.splice(index, 1);
                        setContactPersons(updated);
                      }}
                    >
                      <FaTrash /> Remove
                    </Button>
                  </div>
                ))}
                <Button
                  onClick={() =>
                    setContactPersons([
                      ...contactPersons,
                      {
                        contact_name: '',
                        designation: '',
                        work_email: '',
                        work_phone: ''
                      }
                    ])
                  }
                >
                  + Add Contact
                </Button>
              </Card.Body>
            </Accordion.Collapse>
          </Card>
        </Accordion>
      </Modal.Body>

          <Modal.Footer style={{ backgroundColor: "#f8f9fa" }}>
            <Button variant="secondary" onClick={() => setShowEditModal(false)}>Cancel</Button>
            <Button variant="primary" onClick={handleUpdateCustomer}>Edit Customer</Button>
          </Modal.Footer>
        </Modal>
        





        <Modal show={showEstimateModal} onHide={() => setShowEstimateModal(false)}>
  <Modal.Header closeButton>
    <Modal.Title>Create New Estimate</Modal.Title>
  </Modal.Header>
  <Modal.Body>
    <Form>
      <Form.Group className="mb-3">
        <Form.Label>Customer ID</Form.Label>
        <Form.Control type="text" value={customer?.id || ''} readOnly />
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Estimate Number</Form.Label>
        <Form.Control type="text" value={estimateNumber} readOnly />
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Date</Form.Label>
        <Form.Control type="date" value={estimateDate} onChange={(e) => setEstimateDate(e.target.value)} />
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Amount</Form.Label>
        <Form.Control type="number" value={amount} onChange={(e) => setAmount(e.target.value)} />
      </Form.Group>

      <Form.Group className="mb-3">
        <Form.Label>Status</Form.Label>
        <Form.Select value={status} onChange={(e) => setStatus(e.target.value)}>
          <option value="Pending">Pending</option>
          <option value="Sent">Sent</option>
          <option value="Approved">Approved</option>
        </Form.Select>
      </Form.Group>
    </Form>
  </Modal.Body>
  <Modal.Footer>
    <Button variant="secondary" onClick={() => setShowEstimateModal(false)}>
      Cancel
    </Button>
    <Button variant="primary" onClick={handleCreateEstimate}>
      Create Estimate
    </Button>
  </Modal.Footer>
</Modal>

      </Container>
    </div>
  );
}