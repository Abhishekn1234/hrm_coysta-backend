import React, { useState, useEffect } from 'react';
import { Modal, Button, Form, Row, Col } from 'react-bootstrap';
import axios from 'axios';
import { toast } from 'react-toastify';

function NewProjectModal({ show, onHide, customer, id }) {
  const [staffList, setStaffList] = useState([]);

  const [form, setForm] = useState({
    project_name: '',
    project_description: '',
    project_starting_date: '',
    expected_release_date: '',
    deadline: '',
    product_owner_id: '',
    choice_staffs: [],
    customer_id: id || '',
  });

  useEffect(() => {
    if (show) {
      axios
        .get('http://127.0.0.1:8000/api/v1/staff')
        .then((res) => setStaffList(res.data || []))
        .catch(console.error);
    }
  }, [show]);

  // Ensure customer_id gets updated if the prop changes (e.g. modal reopens)
  useEffect(() => {
    if (id) {
      setForm((prev) => ({ ...prev, customer_id: id }));
    }
  }, [id]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleMultiSelect = (e) => {
    const selected = Array.from(e.target.selectedOptions, (opt) => opt.value);
    setForm((prev) => ({ ...prev, choice_staffs: selected }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await axios.post('http://127.0.0.1:8000/api/v1/projects', form);
      toast.success('✅ Project created successfully!');
      setForm({
        project_name: '',
        project_description: '',
        project_starting_date: '',
        expected_release_date: '',
        deadline: '',
        product_owner_id: '',
        choice_staffs: [],
        customer_id: id || '',
      });
      onHide();
    } catch (err) {
      console.error(err);
      toast.error('❌ Failed to create project.');
    }
  };

  return (
    <Modal show={show} onHide={onHide} centered>
      <Modal.Header closeButton>
        <Modal.Title>
            {`New Project for ${customer|| 'Customer'} EMP ${id}`}
            </Modal.Title>

      </Modal.Header>
      <Form onSubmit={handleSubmit}>
        <Modal.Body>
          <Form.Group>
            <Form.Label>Project Name</Form.Label>
            <Form.Control name="project_name" value={form.project_name} onChange={handleChange} required />
          </Form.Group>

          <Form.Group>
            <Form.Label>Description</Form.Label>
            <Form.Control
              as="textarea"
              name="project_description"
              value={form.project_description}
              onChange={handleChange}
              required
            />
          </Form.Group>

          <Row>
            <Col>
              <Form.Group>
                <Form.Label>Start Date</Form.Label>
                <Form.Control
                  type="date"
                  name="project_starting_date"
                  value={form.project_starting_date}
                  onChange={handleChange}
                  required
                />
              </Form.Group>
            </Col>
            <Col>
              <Form.Group>
                <Form.Label>Expected Release</Form.Label>
                <Form.Control
                  type="date"
                  name="expected_release_date"
                  value={form.expected_release_date}
                  onChange={handleChange}
                  required
                />
              </Form.Group>
            </Col>
          </Row>

          <Form.Group>
            <Form.Label>Deadline</Form.Label>
            <Form.Control type="date" name="deadline" value={form.deadline} onChange={handleChange} required />
          </Form.Group>

          <Form.Group>
            <Form.Label>Product Owner</Form.Label>
            <Form.Select name="product_owner_id" value={form.product_owner_id} onChange={handleChange} required>
              <option value="">Select</option>
              {staffList.map((staff) => (
                <option key={staff.id} value={staff.id}>
                  {staff.name}
                </option>
              ))}
            </Form.Select>
          </Form.Group>

          <Form.Group>
            <Form.Label>Assign Staffs</Form.Label>
            <Form.Select
              multiple
              name="choice_staffs"
              value={form.choice_staffs}
              onChange={handleMultiSelect}
            >
              {staffList.map((staff) => (
                <option key={staff.id} value={staff.id}>
                  {staff.name}
                </option>
              ))}
            </Form.Select>
          </Form.Group>

      
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={onHide}>
            Cancel
          </Button>
          <Button type="submit" variant="primary">
            Create Project
          </Button>
        </Modal.Footer>
      </Form>
    </Modal>
  );
}

export default NewProjectModal;