import React, { useState, useEffect } from "react";
import axios from "axios";

export default function LeadDetailsTabs({ lead, onLeadUpdated }) {

  console.log("leads details NEW",lead );
  // Local state to keep lead data up-to-date in this component
  const [localLead, setLocalLead] = useState(lead || {});
  const [selectedStatus, setSelectedStatus] = useState((lead?.status || 'hot').toLowerCase());
  const [updating, setUpdating] = useState(false);

  // New states for showing status messages below the button
  const [statusMessage, setStatusMessage] = useState('');
  const [statusType, setStatusType] = useState(''); // 'success' or 'error'

  // Reset localLead, selectedStatus and status messages whenever lead prop changes
  useEffect(() => {
    setLocalLead(lead || {});
    setSelectedStatus((lead?.status || 'hot').toLowerCase());
    setStatusMessage('');
    setStatusType('');
  }, [lead]);

  // Auto-clear status message after 3 seconds
  useEffect(() => {
    if (statusMessage) {
      const timer = setTimeout(() => {
        setStatusMessage('');
        setStatusType('');
      }, 3000);
      return () => clearTimeout(timer);
    }
  }, [statusMessage]);

  if (!localLead || Object.keys(localLead).length === 0 || !localLead.id) {
    return (
      <div className="text-center text-muted" style={{ marginTop: '2rem' }}>
        Select a lead to see details
      </div>
    );
  }

  function isMobileDevice() {
    return /Mobi|Android/i.test(navigator.userAgent);
  }

  function handleCall(phone) {
    if (!phone) return;
    recordAction('call');
    window.location.href = `tel:${phone}`;
  }

  function handleWhatsApp(phone) {
    if (!phone) return;
    recordAction('whatsapp');
    const link = isMobileDevice()
      ? `https://wa.me/${phone}`
      : `https://web.whatsapp.com/send?phone=${phone}`;
    window.open(link, '_blank');
  }

  function handleEmail(email) {
    if (!email) return;
    recordAction('email');
    window.location.href = `mailto:${email}`;
  }

  function handleSMS(phone) {
    if (!phone) return;
    recordAction('sms');
    const link = isMobileDevice()
      ? `sms:${phone}`
      : `https://websms.site/?to=${phone}`; // fallback for non-mobile
    window.location.href = link;
  }

  function recordAction(actionType) {
    console.log(`Action performed: ${actionType}`);
    fetch('/api/v1/crm/leads/activities', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ action: actionType, leadId: localLead.id, timestamp: new Date() }),
    }).catch((err) => console.error("Failed to record action:", err));
  }

  // New function: handle status update with axios
  async function handleStatusUpdate() {
    if (selectedStatus === (localLead.status || 'hot').toLowerCase()) return; // no change
    setUpdating(true);
    setStatusMessage('');
    setStatusType('');
    try {
      await axios.patch(`/api/v1/crm/leads/${localLead.id}/status`, { status: selectedStatus });
      // Update local lead state
      const updatedLead = { ...localLead, status: selectedStatus };
      setLocalLead(updatedLead);

      setStatusMessage("Status updated successfully.");
      setStatusType('success');

      // Notify parent of updated lead
      if (typeof onLeadUpdated === 'function') {
        onLeadUpdated(updatedLead);
      }
    } catch (error) {
      setStatusMessage("Failed to update status.");
      setStatusType('error');
      console.error(error);
    } finally {
      setUpdating(false);
    }
  }

  return (
    <div className="card shadow-lg border-0 rounded-3">
      <div className="card-body" style={{ padding: '1rem 0.5rem 0.5rem 0.5rem' }}>
        <div className="row">
          {/* Lead Information */}
          <div className="col-md-6">
            <div
              className="card mb-3"
              style={{
                fontSize: '0.875rem',
                boxShadow: '0 -4px 8px rgba(0, 0, 0, 0.1)',
                borderRadius: '0.5rem'
              }}
            >
              <div className="card-header bg-secondary p-2 text-white">
                <h6 className="card-title mb-0">Lead Information</h6>
              </div>
              <div className="card-body py-2 px-3">
                {[
                  ["Name", localLead.name || '—'],
                  ["Email", localLead.email || '—'],
                  ["Phone", localLead.phone || '—'],
                  ["Company", localLead.company || '—'],
                  ["Source", localLead.leadSource || '—'],
                  [
                    "Status",
                    <span className={`badge bg-${getBadgeColor(localLead.status)}`}>
                      {localLead.status || 'Unknown'}
                    </span>
                  ],
                  ["Assigned", localLead.assigned || '—'],
                ].map(([label, value], idx) => (
                  <div className="row mb-1" key={idx}>
                    <div className="col-5 text-end">
                      <small><strong>{label}:</strong></small>
                    </div>
                    <div className="col-7">{value}</div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Quick Actions and Status Update */}
          <div className="col-md-6">
            <div className="card mb-3 shadow-sm">
              <div className="card-header bg-info p-2 text-white">
                <h6 className="card-title mb-0">Quick Actions</h6>
              </div>
              <div className="card-body p-2">
                <div className="row g-2">
                  <div className="col-6">
                    <button className="btn btn-outline-primary w-100 btn-sm" onClick={() => handleCall(localLead.phone)}>
                      <i className="fas fa-phone-alt"></i> Call
                    </button>
                  </div>
                  <div className="col-6">
                    <button className="btn btn-outline-success w-100 btn-sm" onClick={() => handleWhatsApp(localLead.phone)}>
                      <i className="fab fa-whatsapp"></i> WhatsApp
                    </button>
                  </div>
                  <div className="col-6">
                    <button className="btn btn-outline-danger w-100 btn-sm" onClick={() => handleEmail(localLead.email)}>
                      <i className="fas fa-envelope"></i> Email
                    </button>
                  </div>
                  <div className="col-6">
                    <button className="btn btn-outline-secondary w-100 btn-sm" onClick={() => handleSMS(localLead.phone)}>
                      <i className="fas fa-comment"></i> SMS
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div className="card shadow-sm">
              <div className="card-header bg-dark p-2 text-white">
                <h6 className="card-title mb-0">Update Status</h6>
              </div>
              <div className="card-body p-2">
                <div className="form-group mb-2">
                  <select
                    className="form-control form-control-sm"
                    value={selectedStatus}
                    onChange={(e) => setSelectedStatus(e.target.value)}
                    disabled={updating}
                  >
                    <option value="warm">Warm</option>
                    <option value="hot">Hot</option>
                    <option value="cold">Cold</option>
                    <option value="lost">Lost</option>
                    <option value="client">Client</option>
                  </select>
                </div>
                <div className="form-group mb-2">
                  <select className="form-control form-control-sm" value={localLead.assigned || ''} disabled>
                    <option>Sales Team</option>
                  </select>
                </div>
                <button
                  className="btn btn-dark w-100 btn-sm"
                  onClick={handleStatusUpdate}
                  disabled={updating || selectedStatus === (localLead.status || 'hot').toLowerCase()}
                >
                  <i className="fas fa-save"></i> Update
                </button>
                {/* Status message below button */}
                {statusMessage && (
                  <div
                    className={`mt-2 small ${
                      statusType === 'success' ? 'text-success' : 'text-danger'
                    }`}
                    role="alert"
                  >
                    {statusMessage}
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
        {/* Inside LeadDetailsTabs, just below Lead Information, add this: */}
          <div
            className="card mb-3"
            style={{ fontSize: "0.875rem", boxShadow: "0 -4px 8px rgba(0, 0, 0, 0.1)", borderRadius: "0.5rem" }}
          >
            <div className="card-header bg-warning p-2">
              <h6 className="card-title mb-0"><i className="fas fa-paperclip me-1"></i>Attachments</h6>
            </div>
            <div className="card-body py-2 px-3">
              {localLead?.attachments?.length > 0 ? (
                <div className="row g-2">
                  {localLead.attachments.map((file) => (
                    <div key={file.id} className="col-6 col-md-4">
                      {file.mime_type?.startsWith("image/") ? (
                        <img
                          src={`/storage/${file.path}`}
                          alt={file.original_name}
                          className="img-fluid rounded shadow-sm mb-1"
                          style={{ maxHeight:'100px', objectFit:'cover' }}
                        />
                      ) : (
                        <a
                          href={`/storage/${file.path}`}
                          target="_blank"
                          rel="noreferrer"
                          className="d-block p-2 border rounded text-center"
                        >
                          <i className="fas fa-file-alt me-1"></i> {file.original_name}
                        </a>
                      )}

                      <small className="text-muted d-block">
                        {Math.round(file.size / 1024)} KB
                      </small>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="text-muted small">No attachments</div>
              )}

            </div>
          </div>

      </div>
    </div>
  );
}

function getBadgeColor(status) {
  switch ((status || '').toLowerCase()) {
    case 'hot': return 'danger';
    case 'warm': return 'warning';
    case 'cold': return 'info';
    case 'lost': return 'secondary';
    case 'client': return 'success';
    default: return 'dark';
  }
}
