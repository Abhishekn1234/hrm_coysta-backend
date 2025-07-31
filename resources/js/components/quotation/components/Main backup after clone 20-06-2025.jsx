import React, { useState, useEffect, useCallback, useMemo, useRef } from 'react';
import './quotation.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import axios from 'axios';
import { generateQuotationPdf, processFileAttachments } from './generatePdf';
import jsPDF from 'jspdf';

function Quotation() {
  const [activePage, setActivePage] = useState('list');
  const [customerSearchTerm, setCustomerSearchTerm] = useState('');
  const [filteredCustomers, setFilteredCustomers] = useState([]);
  const [selectedCustomer, setSelectedCustomer] = useState(null);
  const [selectedCustomerType, setSelectedCustomerType] = useState('');
  const [mockCustomers, setMockCustomers] = useState([]);
  const [attachmentPreviews, setAttachmentPreviews] = useState([]);
  const [frontPage, setFrontPage] = useState({
    title: 'QUOTATION',
    content: 'Prepared for: ',
  });
  const [backPage, setBackPage] = useState({
    title: 'TERMS & CONDITIONS',
    content: '1. Payment due within 30 days.\n2. Prices valid for 60 days.\n3. Installation not included unless specified.',
  });
  const [attachments, setAttachments] = useState([]);
  const [isGeneratingPdf, setIsGeneratingPdf] = useState(false);
  const [quotations, setQuotations] = useState([]);
  const [editingQuotation, setEditingQuotation] = useState(null);
  const [searchQuoteTerm, setSearchQuoteTerm] = useState('');
  const [errorMessage, setErrorMessage] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null);
  const [setupDate, setSetupDate] = useState(new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);
  const [packupDate, setPackupDate] = useState(new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);
  const [showActionModal, setShowActionModal] = useState(false);
  const [selectedQuoteId, setSelectedQuoteId] = useState(null);
  const [actionNotes, setActionNotes] = useState('');
  const [actionType, setActionType] = useState('');

  const initialInventoryData = [
    {
      id: 1,
      name: 'Networking Equipment',
      available: 15,
      price: 1200,
      vendor: 'Vendor A',
      days: 10,
      subItems: [
        {
          id: 11,
          name: 'Wireless Access Points',
          available: 8,
          price: 450,
          vendor: 'Vendor B',
          days: 7,
          subItems: [
            {
              id: 111,
              name: 'Enterprise AP Pro',
              available: 50,
              price: 75,
              vendor: 'Vendor C',
              days: 3,
            },
            {
              id: 112,
              name: 'Mesh Wi-Fi System',
              available: 30,
              price: 120,
              vendor: 'Vendor C',
              days: 4,
            },
          ],
        },
        {
          id: 12,
          name: 'Switches',
          available: 12,
          price: 320,
          vendor: 'Vendor A',
          days: 9,
          subItems: [
            {
              id: 121,
              name: '24-Port Gigabit Switch',
              available: 15,
              price: 220,
              vendor: 'Vendor A',
              days: 5,
            },
            {
              id: 122,
              name: '48-Port Managed Switch',
              available: 8,
              price: 520,
              vendor: 'Vendor A',
              days: 6,
            },
          ],
        },
      ],
    },
    {
      id: 2,
      name: 'Cabling Solutions',
      available: 120,
      price: 850,
      vendor: 'Vendor C',
      days: 12,
      subItems: [
        {
          id: 21,
          name: 'Ethernet Cables',
          available: 500,
          price: 3.5,
          vendor: 'Vendor C',
          days: 8,
          subItems: [
            {
              id: 211,
              name: 'Cat6 - 10ft',
              available: 200,
              price: 4.99,
              vendor: 'Vendor C',
              days: 2,
            },
            {
              id: 212,
              name: 'Cat6 - 25ft',
              available: 150,
              price: 8.99,
              vendor: 'Vendor C',
              days: 3,
            },
          ],
        },
        {
          id: 22,
          name: 'Fiber Optic Cables',
          available: 85,
          price: 12.5,
          vendor: 'Vendor B',
          days: 11,
        },
      ],
    },
  ];

  const [inventoryData, setInventoryData] = useState(initialInventoryData);
  const [expandedRows, setExpandedRows] = useState(new Set());
  const [searchTerm, setSearchTerm] = useState('');
  const [vendorFilter, setVendorFilter] = useState('');
  const [selectedItems, setSelectedItems] = useState([]);
  const [includeSetup, setIncludeSetup] = useState(true);
  const [expressShipping, setExpressShipping] = useState(false);
  const [activeTab, setActiveTab] = useState('details');
  const [projectName, setProjectName] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [location, setLocation] = useState('');
  const [subtotal, setSubtotal] = useState(0);
  const [discount, setDiscount] = useState(0);
  const [tax, setTax] = useState(0);
  const [totalAmount, setTotalAmount] = useState(0);
  const [customizationOpen, setCustomizationOpen] = useState(false);

  const customerSearchRef = useRef(null);
  const quoteSearchRef = useRef(null);
  const notesTextareaRef = useRef(null);

  const transformCustomerData = (apiData) => {
    return apiData.map((customer) => ({
      ...customer,
      inventory: customer.inventory.flatMap((item) => ({
        id: item.id || Math.random().toString(36).substr(2, 9),
        name: item['inventory name'],
        available: item.items.reduce((sum, subItem) => sum + subItem.available, 0),
        price: item.items.reduce((sum, subItem) => sum + subItem.price, 0),
        vendor: item.items[0]?.vendor || 'N/A',
        days: item.items[0]?.days || 0,
        subItems: item.items || [],
      })),
    }));
  };

  const fetchInventory = async () => {
    try {
      const response = await axios.get('/api/v1/inventories/customer-inventories');
      const transformedData = transformCustomerData(response.data);
      setMockCustomers(transformedData);
      return transformedData;
    } catch (error) {
      console.error('API error:', error);
      return [];
    }
  };

  const fetchQuotations = async () => {
    try {
      const response = await axios.get('/api/v1/quotations/list');
      setQuotations(response.data.data);
    } catch (error) {
      console.error('Error fetching quotations:', error);
    }
  };

  useEffect(() => {
    fetchInventory();
    fetchQuotations();
  }, []);

  const handleCustomerSearchChange = useCallback((e) => {
    const value = e.target.value;
    setCustomerSearchTerm(value);
    if (value.trim() === '') {
      setFilteredCustomers([]);
      return;
    }
    const filtered = mockCustomers.filter(
      (customer) =>
        customer.name.toLowerCase().includes(value.toLowerCase()) ||
        customer.contactPerson.toLowerCase().includes(value.toLowerCase())
    );
    setFilteredCustomers(filtered);
  }, [mockCustomers]);

  const handleQuoteSearchChange = useCallback((e) => {
    setSearchQuoteTerm(e.target.value);
  }, []);

  const handleActionNotesChange = useCallback((e) => {
    setActionNotes(e.target.value);
  }, []);

  useEffect(() => {
    if (selectedCustomer) {
      setPhone(selectedCustomer.phone || '');
      setEmail(selectedCustomer.email || '');
      setLocation(selectedCustomer.location || '');
      setFrontPage((prev) => ({
        ...prev,
        content: `Prepared for: ${selectedCustomer.name}`,
      }));
    }
  }, [selectedCustomer]);

  useEffect(() => {
    recalcSummary();
  }, [selectedItems, includeSetup, expressShipping]);

  useEffect(() => {
    if (editingQuotation) {
      const customer = mockCustomers.find((c) => c.id === editingQuotation.customer_id);
      if (customer) {
        setSelectedCustomer(customer);
        if (!editingQuotation.isCloned) {
          setInventoryData(customer.inventory || initialInventoryData);
        }
      } else {
        setSelectedCustomer({
          id: editingQuotation.customer_id,
          name: editingQuotation.customer_name,
          phone: editingQuotation.phone || '',
          email: editingQuotation.email || '',
          location: editingQuotation.location || '',
        });
        if (!editingQuotation.isCloned) {
          setInventoryData(initialInventoryData);
        }
      }
      setCustomerSearchTerm(editingQuotation.customer_name || '');
      setProjectName(editingQuotation.project_name || '');
      setSelectedItems(editingQuotation.items || []);
      setSubtotal(Number(editingQuotation.subtotal) || 0);
      setDiscount(Number(editingQuotation.discount) || 0);
      setTax(Number(editingQuotation.tax) || 0);
      setTotalAmount(Number(editingQuotation.total_amount) || 0);
      setIncludeSetup(editingQuotation.include_setup || true);
      setExpressShipping(editingQuotation.express_shipping || false);
      setFrontPage(
        editingQuotation.front_page || {
          title: 'QUOTATION',
          content: `Prepared for: ${editingQuotation.customer_name || ''}`,
        }
      );
      setBackPage(
        editingQuotation.back_page || {
          title: 'TERMS & CONDITIONS',
          content: '1. Payment due within 30 days.\n2. Prices valid for 60 days.\n3. Installation not included unless specified.',
        }
      );
      setAttachments(editingQuotation.attachments || []);
      setActivePage('create');
      setActiveTab('details');
      setSetupDate(
        editingQuotation.setupDate || new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]
      );
      setPackupDate(
        editingQuotation.packupDate || new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]
      );
    }
  }, [editingQuotation, mockCustomers]);

  const handleCustomerSelect = (customer) => {
    setSelectedCustomer(customer);
    setCustomerSearchTerm(customer.name);
    setFilteredCustomers([]);
    setSelectedCustomerType(customer.type || '');
    
    if (!editingQuotation) {
      setInventoryData(customer.inventory || initialInventoryData);
      setSelectedItems([]);
      setExpandedRows(new Set());
    }
  };

  const clearCustomerSelection = () => {
    setSelectedCustomer(null);
    setCustomerSearchTerm('');
    setSelectedCustomerType('');
    
    if (!editingQuotation) {
      setInventoryData(initialInventoryData);
      setSelectedItems([]);
      setExpandedRows(new Set());
    }
  };

  const toggleRow = useCallback((id) => {
    setExpandedRows((prev) => {
      const newSet = new Set(prev);
      if (newSet.has(id)) newSet.delete(id);
      else newSet.add(id);
      return newSet;
    });
  }, []);

  const expandAll = () => {
    const collectParents = (items, parents = new Set()) => {
      items.forEach((item) => {
        if (item.subItems && item.subItems.length) {
          parents.add(item.id);
          collectParents(item.subItems, parents);
        }
      });
      return parents;
    };
    setExpandedRows(collectParents(inventoryData));
  };

  const collapseAll = () => {
    setExpandedRows(new Set());
  };

  const rowMatchesFilter = (item) => {
    const searchTermLower = searchTerm.trim().toLowerCase();
    const matchesName = searchTerm === '' || item.name.toLowerCase().includes(searchTermLower);
    const vendorLower = vendorFilter.toLowerCase();
    const matchesVendor = vendorFilter === '' || item.vendor.toLowerCase() === vendorLower;
    const childMatches =
      item.subItems && item.subItems.length > 0 ? item.subItems.some((child) => rowMatchesFilter(child)) : false;
    return (matchesName && matchesVendor) || childMatches;
  };

  const collectAllItems = (items, collected = []) => {
    items.forEach((item) => {
      collected.push({
        id: item.id,
        name: item.name,
        qty: item.qty || 1,
        price: item.price,
        days: item.days,
        vendor: item.vendor,
        available: item.available,
      });
      if (item.subItems && item.subItems.length > 0) {
        collectAllItems(item.subItems, collected);
      }
    });
    return collected;
  };

  const renderInventoryRows = (items, level = 0, parentId = null) => {
    return items.flatMap((item) => {
      const hasChildren = Array.isArray(item.subItems) && item.subItems.length > 0;
      const isExpanded = expandedRows.has(item.id);
      const visibleBySearch = rowMatchesFilter(item);
      const shouldRender = visibleBySearch && (parentId === null || expandedRows.has(parentId));
      const uniqueKey = parentId ? `${parentId}-${item.id}` : `root-${item.id}`;

      let row = null;
      if (shouldRender) {
        row = (
          <tr
            key={uniqueKey}
            className={`level-${level} ${hasChildren ? 'parent-item' : ''}`}
            data-id={item.id}
          >
            <td>
              <input
                type="checkbox"
                className="form-check-input item-checkbox"
                onChange={(e) => handleItemCheckboxChange(e, item)}
                checked={selectedItems.some((si) => si.id === item.id)}
              />
            </td>
            <td>
              {hasChildren ? (
                <span
                  className={`toggle-symbol ${isExpanded ? 'expanded' : 'collapsed'}`}
                  onClick={() => toggleRow(item.id)}
                >
                  {isExpanded ? '▼' : '▶'}
                </span>
              ) : (
                <span style={{ display: 'inline-block', width: '24px' }}></span>
              )}{' '}
              {item.name}
            </td>
            <td>{item.available}</td>
            <td>
              <input
                type="number"
                className="form-control qty-input"
                min="1"
                max={item.available}
                value={selectedItems.find((si) => si.id === item.id)?.qty ?? 1}
                onChange={(e) => handleQtyChangeInventory(item.id, e.target.value)}
                style={{ maxWidth: '100px' }}
              />
            </td>
            <td>{item.days}</td>
            <td>{item.vendor}</td>
            <td>${item.price.toFixed(2)}</td>
          </tr>
        );
      }

      const childrenRows = [];
      if (hasChildren && isExpanded) {
        childrenRows.push(...renderInventoryRows(item.subItems, level + 1, item.id));
      }

      return shouldRender ? [row, ...childrenRows].filter(Boolean) : [];
    });
  };

  const handleItemCheckboxChange = (e, item) => {
    const checked = e.target.checked;
    setSelectedItems((prev) => {
      if (checked) {
        if (prev.find((si) => si.id === item.id)) return prev;
        return [
          ...prev,
          {
            id: item.id,
            name: item.name,
            qty: 1,
            price: item.price,
            days: item.days,
            vendor: item.vendor,
            available: item.available,
          },
        ];
      } else {
        return prev.filter((si) => si.id !== item.id);
      }
    });
  };

  const handleQtyChangeInventory = (itemId, value) => {
    const newQty = Math.max(1, parseInt(value) || 1);
    setSelectedItems((prev) =>
      prev.map((si) => {
        if (si.id === itemId) {
          const qty = Math.min(newQty, si.available);
          return { ...si, qty };
        }
        return si;
      })
    );
  };

  const handleQtyChangeSelected = (itemId, value) => {
    const newQty = Math.max(1, parseInt(value) || 1);
    setSelectedItems((prev) =>
      prev.map((si) => {
        if (si.id === itemId) {
          const qty = Math.min(newQty, si.available);
          return { ...si, qty };
        }
        return si;
      })
    );
  };

  const removeSelectedItem = (itemId) => {
    setSelectedItems((prev) => prev.filter((si) => si.id !== itemId));
  };

  const handleSelectAll = (e) => {
    const checked = e.target.checked;
    if (checked) {
      const allVisible = [];
      const collectVisible = (items) => {
        items.forEach((item) => {
          if (rowMatchesFilter(item)) {
            allVisible.push({
              id: item.id,
              name: item.name,
              qty: 1,
              days: item.days,
              price: item.price,
              vendor: item.vendor,
              available: item.available,
            });
            if (item.subItems && item.subItems.length > 0 && expandedRows.has(item.id)) {
              collectVisible(item.subItems);
            }
          }
        });
      };
      collectVisible(inventoryData);
      setSelectedItems((prev) => {
        const map = new Map();
        prev.forEach((si) => map.set(si.id, si));
        allVisible.forEach((item) => {
          if (!map.has(item.id)) {
            map.set(item.id, item);
          }
        });
        return Array.from(map.values());
      });
    } else {
      setSelectedItems([]);
    }
  };

  const recalcSummary = useCallback(() => {
    let base = selectedItems.reduce((sum, si) => sum + si.qty * si.price, 0);
    const disc = base * 0.05;
    const taxed = (base - disc) * 0.08;
    let shipping = 75 + (expressShipping ? 50 : 0);
    if (includeSetup) base += 150;
    const total = base - disc + taxed + shipping;

    setSubtotal(base);
    setDiscount(disc);
    setTax(taxed);
    setTotalAmount(total);
  }, [selectedItems, includeSetup, expressShipping]);

  // const handleAttachmentChange = (e) => {
  //   const files = Array.from(e.target.files);
  //   setAttachments((prev) => [...prev, ...files]);
  // };
  const handleAttachmentChange = (e) => {
  const files = Array.from(e.target.files);
  const previews = files
    .filter((file) => file.type.startsWith('image/')) // Only process image files for previews
    .map((file) => URL.createObjectURL(file));

    setAttachments((prev) => [...prev, ...files]);
    setAttachmentPreviews((prev) => [...prev, ...previews]);
  };

  // const removeAttachment = (index) => {
  //   setAttachments((prev) => prev.filter((_, i) => i !== index));
  // };
  const removeAttachment = (index) => {
    setAttachments((prev) => prev.filter((_, i) => i !== index));
    setAttachmentPreviews((prev) => prev.filter((_, i) => i !== index));
  };
  useEffect(() => {
    return () => {
    // Clean up all preview URLs when component unmounts
      attachmentPreviews.forEach((preview) => URL.revokeObjectURL(preview));
    };
  }, [attachmentPreviews]);

  // const handleGeneratePdf = async () => {
  //   setIsGeneratingPdf(true);
  //   setErrorMessage(null);
  //   setSuccessMessage(null);

  //   try {
  //     if (!selectedCustomer) {
  //       throw new Error("Please select a customer first");
  //     }
      
  //     if (!projectName.trim()) {
  //       throw new Error("Project name is required");
  //     }
      
  //     if (selectedItems.length === 0) {
  //       throw new Error("Please select at least one inventory item");
  //     }
  //     const normalize = (str) => (str || '').trim().toLowerCase();
  //     const duplicate = quotations.find(
  //       (q) =>
  //         q.customer_id === selectedCustomer?.id &&
  //         normalize(q.project_name) === normalize(projectName) &&
  //         (!editingQuotation || q.id !== editingQuotation.id)
  //     );

  //     if (duplicate) {
  //       throw new Error(
  //         `A quotation for customer "${selectedCustomer?.name}" and project "${projectName}" already exists (Quotation ID: ${duplicate.quotation_number}).`
  //       );
  //     }

  //     const processedAttachments = await processFileAttachments(attachments);
      
  //     const quotationData = {
  //       customerName: selectedCustomer?.name || 'N/A',
  //       projectName,
  //       date: new Date().toLocaleDateString(),
  //       items: selectedItems,
  //       subtotal,
  //       discount,
  //       tax,
  //       totalAmount,
  //       includeSetup,
  //       expressShipping,
  //       frontPage,
  //       backPage,
  //       attachments: processedAttachments,
  //       setupDate,
  //       packupDate,
  //     };

  //     const { filePath, pdfBlob } = await generateQuotationPdf(quotationData);

  //     const savedQuotation = await saveQuotationData(quotationData, filePath);

  //     const url = window.URL.createObjectURL(pdfBlob);
  //     const link = document.createElement('a');
  //     link.href = url;
  //     link.setAttribute('download', `quotation_${quotationData.customerName}.pdf`);
  //     document.body.appendChild(link);
  //     link.click();
  //     link.remove();
  //     window.URL.revokeObjectURL(url);

  //     setSuccessMessage('PDF generated and saved successfully!');
  //     setTimeout(() => setSuccessMessage(null), 3000);
      
  //   } catch (error) {
  //     console.error('Error:', error);
  //     setErrorMessage(error.message || 'Failed to generate PDF. Please try again.');
  //     setTimeout(() => setErrorMessage(null), 5000);
  //   } finally {
  //     setIsGeneratingPdf(false);
  //   }
  // };

const handleGeneratePdf = async () => {
  setIsGeneratingPdf(true);
  setErrorMessage(null);
  setSuccessMessage(null);

  try {
    if (!selectedCustomer) {
      throw new Error("Please select a customer first");
    }

    if (!projectName.trim()) {
      throw new Error("Project name is required");
    }

    if (selectedItems.length === 0) {
      throw new Error("Please select at least one inventory item");
    }

    const normalize = (str) => (str || '').trim().toLowerCase();
    const isResubmission = editingQuotation?.status === 'rejected';

    // Perform duplicate check only for non-resubmissions
    if (!isResubmission) {
      const duplicate = quotations.find(
        (q) =>
          q.customer_id === selectedCustomer?.id &&
          normalize(q.project_name) === normalize(projectName) &&
          (!editingQuotation || q.id !== editingQuotation.id)
      );

      if (duplicate) {
        throw new Error(
          `A quotation for customer "${selectedCustomer?.name}" and project "${projectName}" already exists (Quotation ID: ${duplicate.quotation_number}).`
        );
      }
    }

    const processedAttachments = await processFileAttachments(attachments);

    const quotationData = {
      customerName: selectedCustomer?.name || 'N/A',
      projectName,
      date: new Date().toLocaleDateString(),
      items: selectedItems,
      subtotal,
      discount,
      tax,
      totalAmount,
      includeSetup,
      expressShipping,
      frontPage,
      backPage,
      attachments: processedAttachments,
      setupDate,
      packupDate,
    };

    const { filePath, pdfBlob } = await generateQuotationPdf(quotationData);

    const savedQuotation = await saveQuotationData(quotationData, filePath);

    const url = window.URL.createObjectURL(pdfBlob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `quotation_${quotationData.customerName}.pdf`);
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);

    setSuccessMessage('PDF generated and saved successfully!');
    setTimeout(() => setSuccessMessage(null), 3000);
  } catch (error) {
    console.error('Error:', error);
    setErrorMessage(error.message || 'Failed to generate PDF. Please try again.');
    setTimeout(() => setErrorMessage(null), 5000);
  } finally {
    setIsGeneratingPdf(false);
  }

};
  const saveQuotationData = async (quotationData, filePath) => {
    try {
      const isResubmission = editingQuotation?.status === 'rejected';

      const response = await axios.post('/api/v1/quotations/saveall', {
        ...quotationData,
        customerId: selectedCustomer?.id || null,
        status: 'draft',
        pdfPath: filePath,
        setupDate: quotationData.setupDate,
        packupDate: quotationData.packupDate,
        is_resubmission: isResubmission,
        parent_quotation_id: isResubmission ? editingQuotation?.id : null,
      });
      
      fetchQuotations();
      return response.data;
      
    } catch (error) {
      console.error('Save error:', error);
      
      const serverMessage = error.response?.data?.message;
      const errorMsg = serverMessage || error.message || 'Failed to save quotation';
      
      throw new Error(errorMsg);
    }
  };

  const handleFrontPageChange = (field, value) => {
    setFrontPage((prev) => ({ ...prev, [field]: value }));
  };

  const handleBackPageChange = (field, value) => {
    setBackPage((prev) => ({ ...prev, [field]: value }));
  };

  const toggleCustomization = () => {
    setCustomizationOpen(!customizationOpen);
  };

  const ActionModal = React.memo(({ isOpen, onClose, actionType, setActionType, actionNotes, setActionNotes, onSubmit, notesRef }) => {
    if (!isOpen) return null;

    return (
      <>
        <div
          className="modal show"
          style={{ display: 'block' }}
          tabIndex="-1"
          role="dialog"
          aria-labelledby="actionModalLabel"
          aria-hidden="true"
        >
          <div className="modal-dialog" role="document">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title" id="actionModalLabel">
                  Quotation Action
                </h5>
                <button
                  type="button"
                  className="btn-close"
                  onClick={onClose}
                  aria-label="Close"
                ></button>
              </div>
              <div className="modal-body">
                <div className="mb-3">
                  <label className="form-label">Action</label>
                  <select
                    className="form-select"
                    value={actionType}
                    onChange={(e) => setActionType(e.target.value)}
                  >
                    <option value="">Select Action</option>
                    <option value="Approved">Approve</option>
                    <option value="Rejected">Reject</option>
                  </select>
                </div>
                <div className="mb-3">
                  <label className="form-label">Notes</label>
                  <textarea
                    className="form-control"
                    rows="4"
                    value={actionNotes}
                    onChange={(e) => setActionNotes(e.target.value)}
                    placeholder="Enter any notes for this action..."
                    ref={notesRef}
                  />
                </div>
              </div>
              <div className="modal-footer">
                <button
                  type="button"
                  className="btn btn-secondary"
                  onClick={onClose}
                >
                  Cancel
                </button>
                <button
                  type="button"
                  className="btn btn-primary"
                  onClick={onSubmit}
                  disabled={!actionType}
                >
                  Submit
                </button>
              </div>
            </div>
          </div>
        </div>
        <div className="modal-backdrop show"></div>
      </>
    );
  });

  const QuotationList = React.memo(() => {
    const [loadingStates, setLoadingStates] = useState({});

    useEffect(() => {
      if (quoteSearchRef.current) {
        quoteSearchRef.current.focus();
      }
    }, []);

    useEffect(() => {
      if (showActionModal && notesTextareaRef.current) {
        notesTextareaRef.current.focus();
      }
    }, [showActionModal]);

    useEffect(() => {
      console.log('Quotations in QuotationList:', quotations);
    }, [quotations]);

    const hasPendingResubmission = useCallback((quotationNumber) => {
      return quotations.some((q) => {
        const isResubmission = q.is_resubmission === 1 || q.is_resubmission === true;
        const isPending = q.status.toLowerCase() === 'pending';
        const isParent = q.parent_quotation_number === quotationNumber;
        return isParent && isResubmission && isPending;
      });
    }, [quotations]);

    const setLoading = (quoteId, action, isLoading) => {
      setLoadingStates((prev) => ({
        ...prev,
        [quoteId]: {
          ...prev[quoteId],
          [action]: isLoading,
        },
      }));
    };

    const handleEditQuotation = useCallback(
      async (quotation) => {
        setLoading(quotation.id, 'edit', true);
        try {
          await new Promise((resolve) => setTimeout(resolve, 500));
          setEditingQuotation(quotation);
        } catch (error) {
          console.error('Error editing quotation:', error);
          setErrorMessage('Failed to load quotation for editing.');
        } finally {
          setLoading(quotation.id, 'edit', false);
        }
      },
      []
    );

    const handleCloneQuotation = useCallback(
      async (quotation) => {
        setLoading(quotation.id, 'clone', true);
        try {
          const customer = mockCustomers.find((c) => c.id === quotation.customer_id) || {
            id: quotation.customer_id,
            name: quotation.customer_name,
            phone: quotation.phone || '',
            email: quotation.email || '',
            location: quotation.location || '',
            inventory: initialInventoryData,
          };

          // Flatten the inventory items for selection
          const flattenedInventory = collectAllItems(customer.inventory || initialInventoryData);

          // Clone selected items, ensuring subitems are included
          const clonedItems = quotation.items ? [...quotation.items] : [];
          const expandedItemIds = new Set();

          // Collect IDs of parent items for selected subitems
          const collectParentIds = (items, selectedItemIds, parentIds = new Set()) => {
            items.forEach((item) => {
              if (item.subItems && item.subItems.length > 0) {
                if (item.subItems.some((subItem) => selectedItemIds.includes(subItem.id))) {
                  parentIds.add(item.id);
                  collectParentIds(item.subItems, selectedItemIds, parentIds);
                }
              }
            });
            return parentIds;
          };

          const selectedItemIds = clonedItems.map((item) => item.id);
          const parentIds = collectParentIds(customer.inventory || initialInventoryData, selectedItemIds);
          expandedItemIds.add(...parentIds);

          const clonedQuotation = {
            ...quotation,
            id: null,
            quotation_number: `QTN-CLONE-${Date.now()}`,
            status: 'draft',
            pdfPath: null,
            is_resubmission: 0,
            parent_quotation_number: null,
            parent_chain: [],
            date: new Date().toISOString().split('T')[0],
            items: clonedItems,
            front_page: quotation.front_page ? { ...quotation.front_page } : { title: 'QUOTATION', content: `Prepared for: ${quotation.customer_name || ''}` },
            back_page: quotation.back_page ? { ...quotation.back_page } : { title: 'TERMS & CONDITIONS', content: '1. Payment due within 30 days.\n2. Prices valid for 60 days.\n3. Installation not included unless specified.' },
            attachments: [],
            phone: customer.phone || '',
            email: customer.email || '',
            location: customer.location || '',
            isCloned: true,
          };

          setSelectedCustomer(customer);
          setCustomerSearchTerm(customer.name || '');
          setInventoryData(customer.inventory || initialInventoryData);
          setSelectedItems(clonedItems);
          setExpandedRows(expandedItemIds);
          setEditingQuotation(clonedQuotation);
          setActivePage('create');
          setSuccessMessage('Quotation cloned successfully. Please review and save.');
          setTimeout(() => setSuccessMessage(null), 3000);
        } catch (error) {
          console.error('Error cloning quotation:', error);
          setErrorMessage('Failed to clone quotation.');
          setTimeout(() => setErrorMessage(null), 5000);
        } finally {
          setLoading(quotation.id, 'clone', false);
        }
      },
      [mockCustomers, initialInventoryData]
    );

    const handleDeleteQuotation = useCallback(
      async (id) => {
        if (window.confirm('Are you sure you want to delete this quotation?')) {
          setLoading(id, 'delete', true);
          try {
            await axios.delete(`/api/v1/quotations/delete/${id}`);
            fetchQuotations();
          } catch (error) {
            console.error('Error deleting quotation:', error);
            setErrorMessage('Failed to delete quotation.');
          } finally {
            setLoading(id, 'delete', false);
          }
        }
      },
      []
    );

    const handleActionClick = useCallback(
      (quoteId) => {
        setSelectedQuoteId(quoteId);
        setActionNotes('');
        setActionType('');
        setShowActionModal(true);
      },
      []
    );

    const handleActionSubmit = useCallback(
      async () => {
        if (!actionType) {
          setErrorMessage('Please select an action (Approve or Reject).');
          return;
        }
        setLoading(selectedQuoteId, 'action', true);
        try {
          await axios.put(`/api/v1/quotations/${selectedQuoteId}/status`, {
            status: actionType.toLowerCase(),
            notes: actionNotes,
          });
          setShowActionModal(false);
          setActionNotes('');
          setActionType('');
          setSelectedQuoteId(null);
          fetchQuotations();
        } catch (error) {
          console.error('Error updating quotation status:', error);
          setErrorMessage('Failed to update quotation status. Please try again.');
        } finally {
          setLoading(selectedQuoteId, 'action', false);
        }
      },
      [actionType, actionNotes, selectedQuoteId]
    );

    const generateItemsPdf = async (quote) => {
      setLoading(quote.id, 'boq', true);
      try {
        if (!quote.items || !Array.isArray(quote.items) || quote.items.length === 0) {
          console.warn('No items available to generate PDF for quotation:', quote.quotation_number);
          setErrorMessage('No items available to generate items PDF.');
          return;
        }
        const doc = new jsPDF();
        doc.setFontSize(18);
        doc.text(`Items List for Quotation ${quote.quotation_number || 'N/A'}`, 14, 20);
        doc.setFontSize(12);
        doc.text(`Customer: ${quote.customer_name || 'N/A'}`, 14, 30);
        doc.text(`Project: ${quote.project_name || 'N/A'}`, 14, 36);
        doc.text(`Date: ${quote.date || new Date().toLocaleDateString()}`, 14, 42);
        doc.setFontSize(14);
        doc.text('Items:', 14, 52);
        let yPos = 62;
        doc.setFontSize(10);
        quote.items.forEach((item, index) => {
          const name = item.name || 'Unnamed Item';
          const qty = item.qty || 1;
          const price = item.price ? item.price.toFixed(2) : '0.00';
          const total = item.qty && item.price ? (item.qty * item.price).toFixed(2) : '0.00';
          doc.text(`${index + 1}. ${name}`, 14, yPos);
          doc.text(`Qty: ${qty}`, 100, yPos);
          doc.text(`Unit Price: $${price}`, 130, yPos);
          doc.text(`Total: $${total}`, 170, yPos);
          yPos += 10;
          if (yPos > 270) {
            doc.addPage();
            yPos = 20;
          }
        });
        const pdfBlob = doc.output('blob');
        const formData = new FormData();
        formData.append('pdf', pdfBlob, `items_${quote.customer_name || 'unknown'}_${quote.quotation_number || 'unknown'}.pdf`);
        formData.append('quotation_id', quote.id || '');
        formData.append('customer_name', quote.customer_name || 'N/A');
        formData.append('quotation_number', quote.quotation_number || 'N/A');
        const response = await axios.post('/api/v1/quotations/save-items-pdf', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        });
        console.log('Items PDF saved:', response.data);
        const url = window.URL.createObjectURL(pdfBlob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `items_${quote.customer_name || 'unknown'}_${quote.quotation_number || 'unknown'}.pdf`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
      } catch (error) {
        console.error('Error generating or saving items PDF:', error);
        setErrorMessage('Failed to generate or save items PDF. Please try again.');
      } finally {
        setLoading(quote.id, 'boq', false);
      }
    };

    const handleDownloadQuotation = async (quote) => {
      setLoading(quote.id, 'download', true);
      try {
        await new Promise((resolve) => setTimeout(resolve, 500));
        const link = document.createElement('a');
        link.href = `http://127.0.0.1:8000/storage/${quote.pdfPath}`;
        link.download = `quotation_${quote.customer_name}.pdf`;
        document.body.appendChild(link);
        link.click();
        link.remove();
      } catch (error) {
        console.error('Error downloading quotation PDF:', error);
        setErrorMessage('Failed to download quotation PDF.');
      } finally {
        setLoading(quote.id, 'download', false);
      }
    };

    const handleModalClose = useCallback(() => {
      setShowActionModal(false);
      setActionNotes('');
      setActionType('');
      setSelectedQuoteId(null);
    }, []);

    return (
      <div className="dashboard-card mt-4">
        <div className="card-header bg-white border-0">
          <div className="d-flex justify-content-between align-items-center">
            <h3 className="mb-0">
              <i className="fas fa-list me-2"></i> Quotation List
            </h3>
            <div className="d-flex gap-2">
              <div className="search-box">
                <i className="fas fa-search"></i>
                <input
                  type="text"
                  placeholder="Search quotations..."
                  value={searchQuoteTerm}
                  onChange={handleQuoteSearchChange}
                  ref={quoteSearchRef}
                />
              </div>
              <button
                className="btn btn-primary"
                onClick={() => {
                  setEditingQuotation(null);
                  setActivePage('create');
                }}
              >
                <i className="fas fa-plus me-2"></i> New Quotation
              </button>
            </div>
          </div>
        </div>
        <div className="stats-summary">
          <div className="row mt-4">
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-primary-subtle p-3 rounded me-3">
                      <i className="fas fa-file-invoice fs-2 text-primary"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Quotations</h5>
                      <h3 className="mb-0">{quotations.length}</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-success-subtle p-3 rounded me-3">
                      <i className="fas fa-check-circle fs-2 text-success"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Approved</h5>
                      <h3 className="mb-0">{quotations.filter((q) => q.status.toLowerCase() === 'approved').length}</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-warning-subtle p-3 rounded me-3">
                      <i className="fas fa-clock fs-2 text-warning"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Pending</h5>
                      <h3 className="mb-0">{quotations.filter((q) => q.status.toLowerCase() === 'pending').length}</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-danger-subtle p-3 rounded me-3">
                      <i className="fas fa-times-circle fs-2 text-danger"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Rejected</h5>
                      <h3 className="mb-0">{quotations.filter((q) => q.status.toLowerCase() === 'rejected').length}</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="card-body">
          <div className="table-responsive">
            <table className="table table-hover">
              <thead>
                <tr>
                  <th>Quotation ID</th>
                  <th>Customer</th>
                  <th>Project</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Resubmission</th>
                  <th>Parent Quotation</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {filteredQuotations.map((quote) => {
                  const parentQuotationNumbers = Array.from(
                    new Set([
                      quote.parent_quotation_number,
                      ...(quote.parent_chain || []).map((parent) => parent.quotation_number),
                    ].filter(Boolean))
                  ).join(', ');
                  const isLoading = loadingStates[quote.id] || {};
                  const isParentWithPendingResubmission = hasPendingResubmission(quote.quotation_number);

                  return (
                    <tr key={quote.id}>
                      <td>{quote.quotation_number}</td>
                      <td>{quote.customer_name}</td>
                      <td>{quote.project_name || '-'}</td>
                      <td>${quote.total_amount.toLocaleString()}</td>
                      <td>{quote.date}</td>
                      <td>
                        <span
                          className={`badge ${
                            quote.status.toLowerCase() === 'approved'
                              ? 'bg-success'
                              : quote.status.toLowerCase() === 'pending'
                              ? 'bg-warning'
                              : quote.status.toLowerCase() === 'rejected'
                              ? 'bg-danger'
                              : 'bg-secondary'
                          }`}
                        >
                          {quote.status.charAt(0).toUpperCase() + quote.status.slice(1)}
                        </span>
                      </td>
                      <td>{quote.is_resubmission ? 'Yes' : 'No'}</td>
                      <td>{parentQuotationNumbers || '-'}</td>
                      <td>
                        <div className="d-flex gap-2">
                          {quote.status.toLowerCase() === 'rejected' && !isParentWithPendingResubmission && (
                            <>
                              {isLoading.edit ? (
                                <div className="spinner-border spinner-border-sm text-primary" role="status">
                                  <span className="visually-hidden">Loading...</span>
                                </div>
                              ) : (
                                <button
                                  className="btn btn-sm btn-outline-primary"
                                  onClick={() => handleEditQuotation(quote)}
                                  title="Revise Submission"
                                >
                                  <i className="fas fa-edit"></i>
                                </button>
                              )}
                            </>
                          )}
                          {quote.status.toLowerCase() === 'rejected' && isParentWithPendingResubmission && (
                            <button
                              className="btn btn-sm btn-outline-primary"
                              disabled
                              title="Cannot revise: A pending resubmission exists."
                            >
                              <i className="fas fa-edit"></i>
                            </button>
                          )}
                          {isLoading.clone ? (
                            <div className="spinner-border spinner-border-sm text-info" role="status">
                              <span className="visually-hidden">Loading...</span>
                            </div>
                          ) : (
                            <button
                              className="btn btn-sm btn-outline-info"
                              onClick={() => handleCloneQuotation(quote)}
                              title="Clone Quotation"
                            >
                              <i className="fas fa-copy"></i>
                            </button>
                          )}
                          {isLoading.delete ? (
                            <div className="spinner-border spinner-border-sm text-danger" role="status">
                              <span className="visually-hidden">Loading...</span>
                            </div>
                          ) : (
                            <button
                              className="btn btn-sm btn-outline-danger"
                              onClick={() => handleDeleteQuotation(quote.id)}
                              title="Delete"
                            >
                              <i className="fas fa-trash"></i>
                            </button>
                          )}
                          {isLoading.download ? (
                            <div className="spinner-border spinner-border-sm text-success" role="status">
                              <span className="visually-hidden">Loading...</span>
                            </div>
                          ) : (
                            <button
                              className="btn btn-sm btn-outline-success"
                              onClick={() => handleDownloadQuotation(quote)}
                              disabled={!quote.pdfPath}
                              title="Download Quotation PDF"
                            >
                              <i className="fas fa-download"></i>
                            </button>
                          )}
                          {isLoading.boq ? (
                            <div className="spinner-border spinner-border-sm text-info" role="status">
                              <span className="visually-hidden">Loading...</span>
                            </div>
                          ) : (
                            <button
                              className="btn btn-sm btn-outline-info"
                              onClick={() => generateItemsPdf(quote)}
                              disabled={!quote.items || quote.items.length === 0}
                              title="Download BOQ"
                            >
                              <i className="fas fa-file-pdf"></i>
                            </button>
                          )}
                          {quote.status.toLowerCase() === 'pending' && (
                            <>
                              {isLoading.action ? (
                                <div className="spinner-border spinner-border-sm text-warning" role="status">
                                  <span className="visually-hidden">Loading...</span>
                                </div>
                              ) : (
                                <button
                                  className="btn btn-sm btn-outline-warning"
                                  onClick={() => handleActionClick(quote.id)}
                                  title="Action"
                                >
                                  <i className="fas fa-cog"></i>
                                </button>
                              )}
                            </>
                          )}
                        </div>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        </div>
        <ActionModal
          isOpen={showActionModal}
          onClose={handleModalClose}
          actionType={actionType}
          setActionType={setActionType}
          actionNotes={actionNotes}
          setActionNotes={setActionNotes}
          onSubmit={handleActionSubmit}
          notesRef={notesTextareaRef}
        />
      </div>
    );
  });

  const filteredQuotations = useMemo(() => {
    return quotations.filter(
      (quote) =>
        quote.customer_name.toLowerCase().includes(searchQuoteTerm.toLowerCase()) ||
        (quote.project_name && quote.project_name.toLowerCase().includes(searchQuoteTerm.toLowerCase()))
    );
  }, [quotations, searchQuoteTerm]);

  const selectedCount = selectedItems.length;

  return (
    <div className="dashboard-container">
      <div className="main-content">
        <div className="header">
          <ul className="nav nav-tabs header-tabs">
            <li className="nav-item">
              <button
                className={`nav-link ${activePage === 'list' ? 'active' : ''}`}
                onClick={() => setActivePage('list')}
              >
                <i className="fas fa-list me-2"></i> Quotation List
              </button>
            </li>
            <li className="nav-item">
              <button
                className={`nav-link ${activePage === 'create' ? 'active' : ''}`}
                onClick={() => setActivePage('create')}
              >
                <i className="fas fa-file-invoice me-2"></i> Create New Quotation
              </button>
            </li>
          </ul>
          <div className="user-menu">
            <div className="user-info">
              <div className="name">Alex Morgan</div>
              <div className="role">Sales Manager</div>
            </div>
            <div className="avatar">AM</div>
          </div>
        </div>
        {errorMessage && (
          <div className="alert alert-danger mt-4 d-flex align-items-center" role="alert">
            <i className="fas fa-exclamation-circle me-2"></i>
            <div>{errorMessage}</div>
            <button
              type="button"
              className="btn-close ms-auto"
              onClick={() => setErrorMessage(null)}
              aria-label="Close"
            ></button>
          </div>
        )}
        {successMessage && (
          <div className="alert alert-success mt-4" role="alert">
            {successMessage}
            <button
              type="button"
              className="btn-close"
              onClick={() => setSuccessMessage(null)}
              aria-label="Close"
            ></button>
          </div>
        )}
        {activePage === 'list' ? (
          <QuotationList />
        ) : (
          <>
            <div className="dashboard-card mt-4">
              <ul className="nav nav-tabs" id="quotationTabs" role="tablist">
                <li className="nav-item" role="presentation">
                  <button
                    className={`nav-link ${activeTab === 'details' ? 'active' : ''}`}
                    onClick={() => setActiveTab('details')}
                  >
                    <i className="fas fa-info-circle me-2"></i> Quotation Details
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className={`nav-link ${activeTab === 'inventory' ? 'active' : ''}`}
                    onClick={() => setActiveTab('inventory')}
                  >
                    <i className="fas fa-boxes me-2"></i> Inventory Selection
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className={`nav-link ${activeTab === 'selected' ? 'active' : ''}`}
                    onClick={() => setActiveTab('selected')}
                  >
                    <i className="fas fa-list-check me-2"></i> Selected Items
                    <span className="badge bg-primary ms-2">
                      {selectedCount} item{selectedCount !== 1 ? 's' : ''}
                    </span>
                  </button>
                </li>
              </ul>
              <div className="tab-content p-3">
                <div className={`tab-pane ${activeTab === 'details' ? 'show active' : ''}`}>
                  <div
                    className="row g-4 p-3 rounded bg-white"
                    style={{ boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)' }}
                  >
                    <div className="col-md-4 position-relative">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Customer <span style={{ color: 'red' }}>*</span>
                      </label>
                      <div className="input-group" style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.2)' }}>
                        <input
                          type="text"
                          className="form-control border-primary-subtle"
                          placeholder="Search customers..."
                          value={customerSearchTerm}
                          onChange={handleCustomerSearchChange}
                          ref={customerSearchRef}
                        />
                        {!selectedCustomer && customerSearchTerm && (
                          <div className="text-danger small mt-1">Please select a customer from the list</div>
                        )}
                        <button className="btn btn-outline-primary">
                          <i className="fas fa-search"></i>
                        </button>
                        {selectedCustomer && (
                          <button
                            className="btn btn-outline-danger"
                            onClick={clearCustomerSelection}
                          >
                            <i className="fas fa-times"></i>
                          </button>
                        )}
                      </div>
                      {filteredCustomers.length > 0 && (
                        <div className="customer-dropdown position-absolute z-10 bg-white mt-1 w-100">
                          {filteredCustomers.map((customer) => (
                            <div
                              key={customer.id}
                              className="p-2 border-bottom"
                              onClick={() => handleCustomerSelect(customer)}
                              style={{
                                cursor: 'pointer',
                                backgroundColor: '#fff',
                                transition: 'background-color 0.2s',
                              }}
                              onMouseOver={(e) => (e.currentTarget.style.backgroundColor = '#f8f9fa')}
                              onMouseOut={(e) => (e.currentTarget.style.backgroundColor = '#fff')}
                            >
                              <div className="fw-bold">{customer.name}</div>
                              <small className="text-muted">
                                {customer.contactPerson} • {customer.type}
                              </small>
                            </div>
                          ))}
                        </div>
                      )}
                    </div>
                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Estimate Type <span style={{ color: 'red' }}>*</span>
                      </label>
                      <select
                        className="form-select border-primary-subtle"
                        required
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      >
                        <option value="">Select Type</option>
                        <option>Project</option>
                        <option>Service</option>
                        <option>Product</option>
                      </select>
                    </div>
                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Customer Type <span style={{ color: 'red' }}>*</span>
                      </label>
                      <select
                        className="form-select border-primary-subtle"
                        value={selectedCustomerType}
                        onChange={(e) => setSelectedCustomerType(e.target.value)}
                        required
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      >
                        <option value="">Select an option</option>
                        <option value="New">New</option>
                        <option value="Existing">Existing</option>
                        <option value="Corporate">Corporate</option>
                      </select>
                    </div>
                    <div className="col-md-6">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Contact Person
                      </label>
                      <input
                        type="text"
                        className="form-control border-secondary-subtle"
                        value={selectedCustomer?.contactPerson || ''}
                        readOnly
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-6">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Project Name <span style={{ color: 'red' }}>*</span>
                      </label>
                      <input
                        type="text"
                        className="form-control border-secondary-subtle"
                        value={projectName}
                        onChange={(e) => setProjectName(e.target.value)}
                        required
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                      {!projectName.trim() && (
                        <div className="text-danger small mt-1">Project name is required</div>
                      )}
                    </div>
                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Phone No
                      </label>
                      <input
                        type="tel"
                        className="form-control"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Client Email
                      </label>
                      <input
                        type="email"
                        className="form-control"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Date <span style={{ color: 'red' }}>*</span>
                      </label>
                      <input
                        type="date"
                        className="form-control"
                        defaultValue={new Date().toISOString().split('T')[0]}
                        required
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Estimate No <span style={{ color: 'red' }}>*</span>
                      </label>
                      <input
                        type="text"
                        className="form-control"
                        defaultValue={`EST-${Math.floor(Math.random() * 10000)}`}
                        readOnly
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Location
                      </label>
                      <input
                        type="text"
                        className="form-control"
                        value={location}
                        onChange={(e) => setLocation(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Venue
                      </label>
                      <input
                        type="text"
                        className="form-control"
                        defaultValue="Main Office"
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Sales Person
                      </label>
                      <input
                        type="text"
                        className="form-control"
                        defaultValue="Alex Morgan"
                        readOnly
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Status
                      </label>
                      <select
                        className="form-select border-warning-subtle"
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      >
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                      </select>
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Start Date
                      </label>
                      <input
                        type="date"
                        className="form-control"
                        defaultValue={new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        End Date
                      </label>
                      <input
                        type="date"
                        className="form-control"
                        defaultValue={new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Set up Date
                      </label>
                      <input
                        type="date"
                        className="form-control"
                        value={setupDate}
                        onChange={(e) => setSetupDate(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>
                        Pack up Date
                      </label>
                      <input
                        type="date"
                        className="form-control"
                        value={packupDate}
                        onChange={(e) => setPackupDate(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                  </div>
                  <div className="d-flex justify-content-between mt-4">
                    <button className="btn btn-outline-primary" disabled>
                      <i className="fas fa-arrow-left me-2"></i> Back
                    </button>
                    <button
                      className="btn btn-primary"
                      onClick={() => setActiveTab('inventory')}
                    >
                      Next <i className="fas fa-arrow-right ms-2"></i>
                    </button>
                  </div>
                </div>
                <div className={`tab-pane ${activeTab === 'inventory' ? 'show active' : ''}`}>
                  <div className="search-container mb-3 d-flex align-items-center gap-2">
                    <div className="search-box d-flex align-items-center">
                      <i className="fas fa-search"></i>
                      <input
                        type="text"
                        className="form-control"
                        placeholder="Search inventory items..."
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                      />
                    </div>
                    <select
                      className="form-select"
                      style={{ maxWidth: '200px' }}
                      value={vendorFilter}
                      onChange={(e) => setVendorFilter(e.target.value)}
                    >
                      <option value="">All Vendors</option>
                      <option>Vendor A</option>
                      <option>Vendor B</option>
                      <option>Vendor C</option>
                      <option>Vendor D</option>
                      <option>Vendor E</option>
                      <option>Vendor F</option>
                      <option>Vendor G</option>
                    </select>
                    <div className="d-flex gap-2 ms-auto">
                      <button className="btn btn-sm btn-outline-primary" onClick={expandAll}>
                        <i className="fas fa-expand"></i> Expand All
                      </button>
                      <button className="btn btn-sm btn-outline-primary" onClick={collapseAll}>
                        <i className="fas fa-compress"></i> Collapse All
                      </button>
                    </div>
                    <div className="form-check">
                      <input
                        type="checkbox"
                        className="form-check-input"
                        onChange={handleSelectAll}
                        checked={
                          (() => {
                            const allIds = [];
                            const collectIds = (items) => {
                              items.forEach((item) => {
                                if (rowMatchesFilter(item)) {
                                  allIds.push(item.id);
                                  if (item.subItems && item.subItems.length > 0 && expandedRows.has(item.id)) {
                                    collectIds(item.subItems);
                                  }
                                }
                              });
                            };
                            collectIds(inventoryData);
                            if (allIds.length === 0) return false;
                            return allIds.every((id) => selectedItems.some((si) => si.id === id));
                          })()
                        }
                      />
                      <label className="form-check-label">Select All</label>
                    </div>
                  </div>
                  <div className="table-responsive">
                    <table className="table tree-table table-hover">
                      <thead className="table-light">
                        <tr>
                          <th style={{ width: '40px' }}></th>
                          <th>Inventory Item</th>
                          <th>Available</th>
                          <th>Quantity</th>
                          <th>Days</th>
                          <th>Vendor</th>
                          <th>Unit Price</th>
                        </tr>
                      </thead>
                      <tbody>{renderInventoryRows(inventoryData)}</tbody>
                    </table>
                  </div>
                  <div className="d-flex justify-content-between mt-4">
                    <button
                      className="btn btn-outline-primary"
                      onClick={() => setActiveTab('details')}
                    >
                      <i className="fas fa-arrow-left me-2"></i> Back
                    </button>
                    <button
                      className="btn btn-primary"
                      onClick={() => setActiveTab('selected')}
                    >
                      Next <i className="fas fa-arrow-right ms-2"></i>
                    </button>
                  </div>
                </div>
                <div className={`tab-pane ${activeTab === 'selected' ? 'show active' : ''}`}>
                  <div className="row">
                    <div className="col-lg-8">
                      <div className="dashboard-card h-100">
                        <div className="card-body">
                          <div className="table-responsive">
                            <table className="table selected-items-table">
                              <thead>
                                <tr>
                                  <th>Item Name</th>
                                  <th>Quantity</th>
                                  <th>Unit Price</th>
                                  <th>Total</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                {selectedItems.map((si) => {
                                  const total = (si.qty * si.price).toFixed(2);
                                  return (
                                    <tr key={si.id}>
                                      <td>{si.name}</td>
                                      <td>
                                        <input
                                          type="number"
                                          className="form-control qty-update"
                                          min="1"
                                          max={si.available}
                                          value={si.qty}
                                          onChange={(e) => handleQtyChangeSelected(si.id, e.target.value)}
                                          style={{ maxWidth: '80px' }}
                                        />
                                      </td>
                                      <td>${si.price.toFixed(2)}</td>
                                      <td>${total}</td>
                                      <td>
                                        <button
                                          className="btn btn-sm btn-danger remove-item"
                                          onClick={() => removeSelectedItem(si.id)}
                                        >
                                          <i className="fas fa-trash"></i>
                                        </button>
                                      </td>
                                    </tr>
                                  );
                                })}
                              </tbody>
                              <tfoot>
                                <tr>
                                  <td colSpan="3" className="text-end fw-bold">
                                    Subtotal
                                  </td>
                                  <td>${subtotal.toFixed(2)}</td>
                                  <td></td>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div className="col-lg-4">
                      <div className="summary-card h-100">
                        <h3 className="mb-4">
                          <i className="fas fa-receipt me-2"></i> Quotation Summary
                        </h3>
                        <div className="summary-item d-flex justify-content-between">
                          <span>Subtotal:</span>
                          <span>${subtotal.toFixed(2)}</span>
                        </div>
                        <div className="summary-item d-flex justify-content-between">
                          <span>Discount (5%):</span>
                          <span>${discount.toFixed(2)}</span>
                        </div>
                        <div className="summary-item d-flex justify-content-between">
                          <span>Tax (8%):</span>
                          <span>${tax.toFixed(2)}</span>
                        </div>
                        <div className="summary-item d-flex justify-content-between">
                          <span>Shipping:</span>
                          <span>${expressShipping ? 125 : 75}.00</span>
                        </div>
                        <div className="summary-item d-flex justify-content-between">
                          <span>Total:</span>
                          <span>${totalAmount.toFixed(2)}</span>
                        </div>
                        <div className="mt-4">
                          <div className="form-check form-switch mb-3">
                            <input
                              className="form-check-input"
                              type="checkbox"
                              id="includeSetup"
                              checked={includeSetup}
                              onChange={(e) => setIncludeSetup(e.target.checked)}
                            />
                            <label className="form-check-label" htmlFor="includeSetup">
                              Include setup fee ($150)
                              <span style={{ color: includeSetup ? 'green' : 'red', marginLeft: '5px' }}>
                                {includeSetup ? '✓ Included' : '✗ Not Included'}
                              </span>
                            </label>
                          </div>
                          <div className="form-check form-switch">
                            <input
                              className="form-check-input"
                              type="checkbox"
                              id="expressShipping"
                              checked={expressShipping}
                              onChange={(e) => setExpressShipping(e.target.checked)}
                            />
                            <label className="form-check-label" htmlFor="expressShipping">
                              Express shipping (+$50)
                            </label>
                          </div>
                        </div>
                        <div className="accordion mt-4" id="pdfCustomizationAccordion">
                          <div className="accordion-item">
                            <h2 className="accordion-header">
                              <button
                                className={`accordion-button ${customizationOpen ? '' : 'collapsed'}`}
                                type="button"
                                onClick={toggleCustomization}
                              >
                                PDF Customization Options
                              </button>
                            </h2>
                            <div
                              id="pdfCustomization"
                              className={`accordion-collapse collapse ${customizationOpen ? 'show' : ''}`}
                            >
                              <div className="accordion-body">
                                <div className="row">
                                  <div className="col-md-6">
                                    <h5>Front Page</h5>
                                    <div className="mb-3">
                                      <label className="form-label">Title</label>
                                      <input
                                        type="text"
                                        className="form-control"
                                        value={frontPage.title}
                                        onChange={(e) => handleFrontPageChange('title', e.target.value)}
                                      />
                                    </div>
                                    <div className="mb-3">
                                      <label className="form-label">Content</label>
                                      <textarea
                                        className="form-control"
                                        rows="3"
                                        value={frontPage.content}
                                        onChange={(e) => handleFrontPageChange('content', e.target.value)}
                                      />
                                    </div>
                                  </div>
                                  <div className="col-md-6">
                                    <h5>Back Page</h5>
                                    <div className="mb-3">
                                      <label className="form-label">Title</label>
                                      <input
                                        type="text"
                                        className="form-control"
                                        value={backPage.title}
                                        onChange={(e) => handleBackPageChange('title', e.target.value)}
                                      />
                                    </div>
                                    <div className="mb-3">
                                      <label className="form-label">Content</label>
                                      <textarea
                                        className="form-control"
                                        rows="3"
                                        value={backPage.content}
                                        onChange={(e) => handleBackPageChange('content', e.target.value)}
                                      />
                                    </div>
                                  </div>
                                </div>
                                <div className="mb-3">
                                  <label className="form-label">Attachments</label>
                                  <input
                                    type="file"
                                    className="form-control"
                                    multiple
                                    onChange={handleAttachmentChange}
                                  />
                                  {attachments.length > 0 && (
                                    <ul className="list-group mt-2">
                                      {attachments.map((file, index) => (
                                        <li
                                          key={index}
                                          className="list-group-item d-flex justify-content-between align-items-center"
                                        >
                                          <div className="d-flex align-items-center">
                                            {file.type.startsWith('image/') && attachmentPreviews[index] ? (
                                              <>
                                                <img
                                                  src={attachmentPreviews[index]}
                                                  alt={file.name}
                                                  style={{ width: '50px', height: '50px', objectFit: 'cover', marginRight: '10px' }}
                                                />
                                                {file.name}
                                              </>
                                            ) : (
                                              file.name
                                            )}
                                          </div>
                                          <button
                                            className="btn btn-sm btn-danger"
                                            onClick={() => removeAttachment(index)}
                                          >
                                            <i className="fas fa-trash"></i>
                                          </button>
                                        </li>
                                      ))}
                                    </ul>
                                  )}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <button
                          className="btn btn-success w-100 mt-4"
                          onClick={handleGeneratePdf}
                          disabled={isGeneratingPdf}
                        >
                          {isGeneratingPdf ? (
                            <>
                              <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                              Generating...
                            </>
                          ) : (
                            <>
                              <i className="fas fa-file-pdf me-2"></i> Generate PDF
                            </>
                          )}
                        </button>
                      </div>
                    </div>
                  </div>
                  <div className="d-flex justify-content-between mt-4">
                    <button
                      className="btn btn-outline-primary"
                      onClick={() => setActiveTab('inventory')}
                    >
                      <i className="fas fa-arrow-left me-2"></i> Back
                    </button>
                    <button
                      className="btn btn-primary"
                      onClick={() => setActivePage('list')}
                    >
                      Finish <i className="fas fa-check ms-2"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </>
        )}
      </div>
    </div>
  );
}

export default Quotation;