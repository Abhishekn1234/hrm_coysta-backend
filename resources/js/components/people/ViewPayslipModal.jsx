import React, { useRef } from 'react';
import { Modal, Button, Row, Col } from 'react-bootstrap';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';
import './Payslip.css';

const ViewPayslipModal = ({ show, onHide, staff }) => {
  const printRef = useRef(null);
  if (!staff) return null;

  const currentMonth = new Date().toLocaleString('default', { month: 'long', year: 'numeric' });

  const totalEarnings = parseFloat(staff.daily_remuneration || 0) * 30;
  const totalDeductions =
    parseFloat(staff.tax || 0) + parseFloat(staff.tds || 0) + parseFloat(staff.loan || 0);
  const netPay = totalEarnings - totalDeductions;

  const handlePDFDownload = async () => {
    const input = printRef.current;
    const canvas = await html2canvas(input, { scale: 2 });
    const imgData = canvas.toDataURL('image/png');
    const pdf = new jsPDF('p', 'pt', 'a4');
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    pdf.save(`Payslip_${staff.name}_${currentMonth}.pdf`);
  };

  return (
    <Modal show={show} onHide={onHide} centered size="lg" dialogClassName="print-container">
      <Modal.Header className="bg-success text-white no-print">
        <Modal.Title>
          Payroll Details – {staff.name} ({staff.id}) – {currentMonth}
        </Modal.Title>
        <button type="button" className="btn-close btn-close-white" onClick={onHide} />
      </Modal.Header>

      <Modal.Body ref={printRef} className="payslip-pdf-container p-4">
        <h5 className="text-center mb-4">Payroll Details for {staff.name}</h5>

        <div className="mb-3">
          <strong>Employee ID:</strong> {staff.id}
        </div>

        <Row className="mb-3">
          <Col md={6}>
            <h6 className="border-bottom pb-1">Earnings</h6>
            <ul className="list-unstyled">
              <li className="d-flex justify-content-between">
                <span>Basic Salary</span>
                <span>₹{(staff.daily_remuneration * 25).toLocaleString()}</span>
              </li>
              <li className="d-flex justify-content-between">
                <span>House Rent Allowance</span>
                <span>₹{(staff.housing_allowance * 3).toLocaleString()}</span>
              </li>
              <li className="d-flex justify-content-between">
                <span>Transport Allowance</span>
                <span>₹{(staff.transport_allowance * 2).toLocaleString()}</span>
              </li>
              <li className="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                <span>Total Earnings</span>
                <span>₹{totalEarnings.toLocaleString()}</span>
              </li>
            </ul>
          </Col>

          <Col md={6}>
            <h6 className="border-bottom pb-1">Deductions</h6>
            <ul className="list-unstyled">
              <li className="d-flex justify-content-between">
                <span>Professional Tax</span>
                <span>₹{staff.tax || 0}</span>
              </li>
              <li className="d-flex justify-content-between">
                <span>TDS</span>
                <span>₹{staff.tds || 0}</span>
              </li>
              <li className="d-flex justify-content-between">
                <span>Loan Recovery</span>
                <span>₹{staff.loan || 0}</span>
              </li>
              <li className="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                <span>Total Deductions</span>
                <span>₹{totalDeductions.toLocaleString()}</span>
              </li>
            </ul>
          </Col>
        </Row>

        <h5 className="text-center border-top pt-3">Net Pay: ₹{netPay.toLocaleString()}</h5>

        <div className="text-center mt-4 small text-muted fst-italic">
          *This is a system-generated payslip and does not require a signature.
        </div>
      </Modal.Body>

      <Modal.Footer className="no-print d-flex justify-content-between">
        <Button variant="secondary" onClick={onHide}>Close</Button>
        <div>
          <Button
            variant="success"
            className="me-2"
            onClick={() => alert('✅ Payment processed successfully!')}
          >
            Process Payment
          </Button>
          <Button variant="primary" onClick={handlePDFDownload}>Download PDF</Button>
        </div>
      </Modal.Footer>
    </Modal>
  );
};

export default ViewPayslipModal;