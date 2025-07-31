import React, { useState } from 'react';
import { Modal, Button, Form } from 'react-bootstrap';
import axios from 'axios';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const InvoiceModal = ({ show, onClose, customerId }) => {
  const [formData, setFormData] = useState({
    issue_date: '',
    due_date: '',
    amount: '',
    status: 'Pending'
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async () => {
    try {
      await axios.post(`http://127.0.0.1:8000/api/v1/customers/${customerId}/invoices`, formData);
      toast.success('✅ Invoice created successfully!');
      setFormData({
          issue_date: '',
    due_date: '',
    amount: '',
    status: 'Pending'
      });
      onClose();
      
    } catch (error) {
      toast.error('❌ Failed to create invoice.');
      console.error(error.response?.data || error.message);
    }
  };

  return (
    <>
      <ToastContainer position="top-right" autoClose={3000} />
      <Modal show={show} onHide={onClose}>
        <Modal.Header closeButton>
          <Modal.Title>New Invoice</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form>
            <Form.Group className="mb-3">
              <Form.Label>Issue Date</Form.Label>
              <Form.Control type="date" name="issue_date" onChange={handleChange} required />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Due Date</Form.Label>
              <Form.Control type="date" name="due_date" onChange={handleChange} required />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Amount</Form.Label>
              <Form.Control type="number" name="amount" onChange={handleChange} required />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Status</Form.Label>
              <Form.Select name="status" onChange={handleChange} value={formData.status}>
                <option value="Pending">Pending</option>
                <option value="Paid">Paid</option>
                <option value="Overdue">Overdue</option>
              </Form.Select>
            </Form.Group>
          </Form>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={onClose}>Close</Button>
          <Button variant="primary" onClick={handleSubmit}>Save Invoice</Button>
        </Modal.Footer>
      </Modal>
    </>
  );
};

export default InvoiceModal;