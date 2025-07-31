import React, { useState } from 'react';

import { Modal, Button, Form, Toast, ToastContainer } from 'react-bootstrap';
import axios from 'axios';

export default function CreatePOModal({ show, handleClose, vendor }) {
  const [poId, setPoId] = useState('');
  const [date, setDate] = useState(new Date().toISOString().split('T')[0]);
  const [amount, setAmount] = useState('');
  const [status, setStatus] = useState('Ordered');

  const [showToast, setShowToast] = useState(false);
  const [toastMessage, setToastMessage] = useState('');
  const [toastVariant, setToastVariant] = useState('success');

  const handleSubmit = async () => {
    try {
     const res = await axios.post(`http://127.0.0.1:8000/api/v1/vendors/${vendor.id}/purchase-orders`, {

        date,
        amount,
        status
      });

      setPoId(res.data.po_id);
      setToastVariant('success');
      setToastMessage(`Purchase Order Created: ${res.data.po_id}`);
      setShowToast(true);
      handleClose();
    } catch (error) {
      console.error('Error creating PO:', error);
      setToastVariant('danger');
      setToastMessage('Failed to create Purchase Order.');
      setShowToast(true);
    }
  };

  return (
    <>
      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Create New Purchase Order</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form>
            <Form.Group className="mb-3">
              <Form.Label>Date</Form.Label>
              <Form.Control
                type="date"
                value={date}
                onChange={(e) => setDate(e.target.value)}
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Amount</Form.Label>
              <Form.Control
                type="number"
                placeholder="Enter amount"
                value={amount}
                onChange={(e) => setAmount(e.target.value)}
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Status</Form.Label>
              <Form.Select value={status} onChange={(e) => setStatus(e.target.value)}>
                <option value="Ordered">Ordered</option>
                <option value="Pending">Pending</option>
                <option value="Delivered">Delivered</option>
              </Form.Select>
            </Form.Group>
          </Form>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>Cancel</Button>
          <Button variant="primary" onClick={handleSubmit}>Create PO</Button>
        </Modal.Footer>
      </Modal>

      <ToastContainer position="bottom-end" className="p-3">
        <Toast
          onClose={() => setShowToast(false)}
          show={showToast}
          bg={toastVariant}
          delay={3000}
          autohide
        >
          <Toast.Header>
            <strong className="me-auto">Purchase Order</strong>
          </Toast.Header>
          <Toast.Body className="text-white">{toastMessage}</Toast.Body>
        </Toast>
      </ToastContainer>
    </>
  );
}