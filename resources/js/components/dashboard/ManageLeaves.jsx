import React, { useState,useEffect } from "react";
import { Form, Button, Row, Col, Card, Alert } from "react-bootstrap";
import { FiCalendar, FiAlertTriangle, FiCheckCircle } from "react-icons/fi";

export default function ManageLeaves({ onClose }) {
  const [leaveData, setLeaveData] = useState({
    leaveType: "",
    fromDate: "",
    toDate: "",
    reason: "",
    isEmergency: false
  });

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

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setLeaveData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log("Leave Request Submitted:", leaveData);
    alert("Leave request submitted successfully!");
    onClose(); // Close modal after submission
  };

  const handleAutoApprove = () => {
    alert("Leave auto-approved based on AI recommendation!");
    onClose(); // Close modal after auto-approval
  };

  return (
   
      
      <Form onSubmit={handleSubmit}>
       <Form.Group className="mb-4">
  <Form.Label><strong>Leave Type</strong></Form.Label>
  <Form.Select
    name="leaveType"
    value={leaveData.leaveType}
    onChange={handleChange}
    required
  >
    <option value="">Select Leave Type</option>
    {leaveTypes.map((type, index) => (
      <option key={index} value={type}>{type}</option>
    ))}
  </Form.Select>
</Form.Group>


        <Row className="mb-4">
          <Col md={6}>
            <Form.Group>
              <Form.Label><strong>From Date</strong></Form.Label>
              <div className="d-flex align-items-center">
                <FiCalendar className="me-2" style={{ color: "#2b90d9" }} />
                <Form.Control
                  type="date"
                  name="fromDate"
                  value={leaveData.fromDate}
                  onChange={handleChange}
                  required
                />
              </div>
            </Form.Group>
          </Col>
          <Col md={6}>
            <Form.Group>
              <Form.Label><strong>To Date</strong></Form.Label>
              <div className="d-flex align-items-center">
                <FiCalendar className="me-2" style={{ color: "#2b90d9" }} />
                <Form.Control
                  type="date"
                  name="toDate"
                  value={leaveData.toDate}
                  onChange={handleChange}
                  required
                />
              </div>
            </Form.Group>
          </Col>
        </Row>

        <Form.Group className="mb-4">
          <Form.Label><strong>Reason</strong></Form.Label>
          <Form.Control
            as="textarea"
            rows={3}
            placeholder="Enter reason for leave"
            name="reason"
            value={leaveData.reason}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-4">
          <Form.Check
            type="checkbox"
            label={
              <>
                
                Emergency Leave
              </>
            }
            name="isEmergency"
            checked={leaveData.isEmergency}
            onChange={handleChange}
          />
        </Form.Group>

        <Alert variant="info" className="d-flex align-items-center">
          <FiCheckCircle className="me-2" size={20} />
          <strong>AI Approval:</strong> Based on past patterns, 92% chance this leave will be approved.
        </Alert>

        <div className="d-flex justify-content-between mt-4">
          <Button variant="outline-secondary" onClick={onClose}>
            Cancel
          </Button>
          <div>
            <Button variant="primary" type="submit" className="me-2">
              Submit Request
            </Button>
            <Button variant="success" onClick={handleAutoApprove}>
              Auto-Approve
            </Button>
          </div>
        </div>
      </Form>
  
  );
}