import React, { useState, useEffect, useCallback } from 'react';
import './quotation.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import axios from 'axios';
import { generateQuotationPdf, processFileAttachments } from './generatePdf';

function Quotation() {
  const [activePage, setActivePage] = useState('create');
  const [customerSearchTerm, setCustomerSearchTerm] = useState("");
  const [filteredCustomers, setFilteredCustomers] = useState([]);
  const [selectedCustomer, setSelectedCustomer] = useState(null);
  const [selectedCustomerType, setSelectedCustomerType] = useState('');
  const [mockCustomers, setMockCustomers] = useState([]);
  const [frontPage, setFrontPage] = useState({
    title: 'QUOTATION',
    content: 'Prepared for: '
  });
  const [backPage, setBackPage] = useState({
    title: 'TERMS & CONDITIONS',
    content: '1. Payment due within 30 days.\n2. Prices valid for 60 days.\n3. Installation not included unless specified.'
  });
  const [attachments, setAttachments] = useState([]);
  const [isGeneratingPdf, setIsGeneratingPdf] = useState(false);
  const [quotations, setQuotations] = useState([]);
  const [editingQuotation, setEditingQuotation] = useState(null);
  const [searchQuoteTerm, setSearchQuoteTerm] = useState("");
  // NEW: State for error feedback
  const [errorMessage, setErrorMessage] = useState(null);
  const [setupDate, setSetupDate] = useState(new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);
  const [packupDate, setPackupDate] = useState(new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);

  const initialInventoryData = [
    {
      id: 1,
      name: "Networking Equipment",
      available: 15,
      price: 1200,
      vendor: "Vendor A",
      days: 10,
      subItems: [
        {
          id: 11,
          name: "Wireless Access Points",
          available: 8,
          price: 450,
          vendor: "Vendor B",
          days: 7,
          subItems: [
            {
              id: 111,
              name: "Enterprise AP Pro",
              available: 50,
              price: 75,
              vendor: "Vendor C",
              days: 3,
            },
            {
              id: 112,
              name: "Mesh Wi-Fi System",
              available: 30,
              price: 120,
              vendor: "Vendor C",
              days: 4,
            },
          ],
        },
        {
          id: 12,
          name: "Switches",
          available: 12,
          price: 320,
          vendor: "Vendor A",
          days: 9,
          subItems: [
            {
              id: 121,
              name: "24-Port Gigabit Switch",
              available: 15,
              price: 220,
              vendor: "Vendor A",
              days: 5,
            },
            {
              id: 122,
              name: "48-Port Managed Switch",
              available: 8,
              price: 520,
              vendor: "Vendor A",
              days: 6,
            },
          ],
        },
      ],
    },
    {
      id: 2,
      name: "Cabling Solutions",
      available: 120,
      price: 850,
      vendor: "Vendor C",
      days: 12,
      subItems: [
        {
          id: 21,
          name: "Ethernet Cables",
          available: 500,
          price: 3.5,
          vendor: "Vendor C",
          days: 8,
          subItems: [
            {
              id: 211,
              name: "Cat6 - 10ft",
              available: 200,
              price: 4.99,
              vendor: "Vendor C",
              days: 2,
            },
            {
              id: 212,
              name: "Cat6 - 25ft",
              available: 150,
              price: 8.99,
              vendor: "Vendor C",
              days: 3,
            },
          ],
        },
        {
          id: 22,
          name: "Fiber Optic Cables",
          available: 85,
          price: 12.5,
          vendor: "Vendor B",
          days: 11,
        },
      ],
    },
  ];

  const [inventoryData, setInventoryData] = useState(initialInventoryData);
  const [expandedRows, setExpandedRows] = useState(new Set());
  const [searchTerm, setSearchTerm] = useState("");
  const [vendorFilter, setVendorFilter] = useState("");
  const [selectedItems, setSelectedItems] = useState([]);
  const [includeSetup, setIncludeSetup] = useState(true);
  const [expressShipping, setExpressShipping] = useState(false);
  const [activeTab, setActiveTab] = useState('details');
  const [projectName, setProjectName] = useState("");
  const [phone, setPhone] = useState("");
  const [email, setEmail] = useState("");
  const [location, setLocation] = useState("");
  const [subtotal, setSubtotal] = useState(0);
  const [discount, setDiscount] = useState(0);
  const [tax, setTax] = useState(0);
  const [totalAmount, setTotalAmount] = useState(0);
  const [customizationOpen, setCustomizationOpen] = useState(false);

  const transformCustomerData = (apiData) => {
    return apiData.map(customer => ({
      ...customer,
      inventory: customer.inventory.flatMap(item => ({
        id: item.id || Math.random().toString(36).substr(2, 9),
        name: item['inventory name'],
        available: item.items.reduce((sum, subItem) => sum + subItem.available, 0),
        price: item.items.reduce((sum, subItem) => sum + subItem.price, 0),
        vendor: item.items[0]?.vendor || 'N/A',
        days: item.items[0]?.days || 0,
        subItems: item.items || []
      }))
    }));
  };

  const fetchInventory = async () => {
    try {
      const response = await axios.get('/api/v1/inventories/customer-inventories');
      const transformedData = transformCustomerData(response.data);
      setMockCustomers(transformedData);
      return transformedData;
    } catch (error) {
      console.error("API error:", error);
      return [];
    }
  };

  const fetchQuotations = async () => {
    try {
      const response = await axios.get('/api/v1/quotations/list');
      setQuotations(response.data.data);
    } catch (error) {
      console.error("Error fetching quotations:", error);
    }
  };

  useEffect(() => {
    fetchInventory();
    fetchQuotations();
  }, []);

  useEffect(() => {
    if (customerSearchTerm.trim() === "") {
      setFilteredCustomers([]);
      return;
    }
    
    const filtered = mockCustomers.filter(customer => 
      customer.name.toLowerCase().includes(customerSearchTerm.toLowerCase()) ||
      customer.contactPerson.toLowerCase().includes(customerSearchTerm.toLowerCase())
    );
    setFilteredCustomers(filtered);
  }, [customerSearchTerm, mockCustomers]);

  useEffect(() => {
    if (selectedCustomer) {
      setPhone(selectedCustomer.phone);
      setEmail(selectedCustomer.email);
      setLocation(selectedCustomer.location);
      setFrontPage(prev => ({
        ...prev,
        content: `Prepared for: ${selectedCustomer.name}`
      }));
    }
  }, [selectedCustomer]);

  useEffect(() => {
    recalcSummary();
  }, [selectedItems, includeSetup, expressShipping]);

  useEffect(() => {
    if (editingQuotation) {
      const customer = mockCustomers.find(c => c.id === editingQuotation.customer_id);
      
      if (customer) {
        setSelectedCustomer(customer);
        setInventoryData(customer.inventory);
      } else {
        setSelectedCustomer({ 
          id: editingQuotation.customer_id, 
          name: editingQuotation.customer_name 
        });
        setInventoryData(initialInventoryData);
      }

      setCustomerSearchTerm(editingQuotation.customer_name);
      setProjectName(editingQuotation.project_name || "");
      setSelectedItems(editingQuotation.items);
      setSubtotal(Number(editingQuotation.subtotal) || 0);
      setDiscount(Number(editingQuotation.discount) || 0);
      setTax(Number(editingQuotation.tax) || 0);
      setTotalAmount(Number(editingQuotation.total_amount) || 0);
      setIncludeSetup(editingQuotation.include_setup);
      setExpressShipping(editingQuotation.express_shipping);
      setFrontPage(editingQuotation.front_page || {
        title: 'QUOTATION',
        content: `Prepared for: ${editingQuotation.customer_name}`
      });
      setBackPage(editingQuotation.back_page || {
        title: 'TERMS & CONDITIONS',
        content: '1. Payment due within 30 days.\n2. Prices valid for 60 days.\n3. Installation not included unless specified.'
      });
      setAttachments(editingQuotation.attachments || []);
      setActivePage('create');
      setActiveTab('details');
      setSetupDate(editingQuotation.setupDate || new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);
      setPackupDate(editingQuotation.packupDate || new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]);
    }
  }, [editingQuotation, mockCustomers]);

  const handleCustomerSelect = (customer) => {
    setSelectedCustomer(customer);
    setCustomerSearchTerm(customer.name);
    setFilteredCustomers([]);
    setSelectedCustomerType(customer.type);
    setInventoryData(customer.inventory);
    setSelectedItems([]);
    setExpandedRows(new Set());
  };

  const clearCustomerSelection = () => {
    setSelectedCustomer(null);
    setCustomerSearchTerm("");
    setSelectedCustomerType("");
    setInventoryData(initialInventoryData);
    setSelectedItems([]);
    setExpandedRows(new Set());
  };

  const toggleRow = useCallback((id) => {
    setExpandedRows(prev => {
      const newSet = new Set(prev);
      if (newSet.has(id)) newSet.delete(id);
      else newSet.add(id);
      return newSet;
    });
  }, []);

  const expandAll = () => {
    const collectParents = (items, parents = new Set()) => {
      items.forEach(item => {
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
    const matchesName = searchTerm === "" || 
      item.name.toLowerCase().includes(searchTermLower);
    
    const vendorLower = vendorFilter.toLowerCase();
    const matchesVendor = vendorFilter === "" || 
      item.vendor.toLowerCase() === vendorLower;
    
    const childMatches = item.subItems && item.subItems.length > 0 
      ? item.subItems.some(child => rowMatchesFilter(child))
      : false;

    return (matchesName && matchesVendor) || childMatches;
  };

  const renderInventoryRows = (items, level = 0, parentId = null) => {
    return items.flatMap((item, index) => {
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
            className={`level-${level} ${hasChildren ? "parent-item" : ""}`}
            data-id={item.id}
          >
            <td>
              <input
                type="checkbox"
                className="form-check-input item-checkbox"
                onChange={(e) => handleItemCheckboxChange(e, item)}
                checked={selectedItems.some(si => si.id === item.id)}
              />
            </td>
            <td>
              {hasChildren ? (
                <span
                  className={`toggle-symbol ${isExpanded ? "expanded" : "collapsed"}`}
                  onClick={() => toggleRow(item.id)}
                >
                  {isExpanded ? "▼" : "▶"}
                </span>
              ) : (
                <span style={{ display: "inline-block", width: "24px" }}></span>
              )}{" "}
              {item.name}
            </td>
            <td>{item.available}</td>
            <td>
              <input
                type="number"
                className="form-control qty-input"
                min="1"
                max={item.available}
                value={selectedItems.find(si => si.id === item.id)?.qty ?? 1}
                onChange={(e) => handleQtyChangeInventory(item.id, e.target.value)}
                style={{ maxWidth: "100px" }}
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
    setSelectedItems(prev => {
      if (checked) {
        if (prev.find(si => si.id === item.id)) return prev;
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
          }
        ];
      } else {
        return prev.filter(si => si.id !== item.id);
      }
    });
  };

  const handleQtyChangeInventory = (itemId, value) => {
    const newQty = Math.max(1, parseInt(value) || 1);
    setSelectedItems(prev =>
      prev.map(si => {
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
    setSelectedItems(prev =>
      prev.map(si => {
        if (si.id === itemId) {
          const qty = Math.min(newQty, si.available);
          return { ...si, qty };
        }
        return si;
      })
    );
  };

  const removeSelectedItem = (itemId) => {
    setSelectedItems(prev => prev.filter(si => si.id !== itemId));
  };

  const handleSelectAll = (e) => {
    const checked = e.target.checked;
    if (checked) {
      const allVisible = [];
      const collectVisible = (items) => {
        items.forEach(item => {
          if (rowMatchesFilter(item)) {
            allVisible.push(item);
            if (item.subItems && item.subItems.length > 0 && expandedRows.has(item.id)) {
              collectVisible(item.subItems);
            }
          }
        });
      };
      collectVisible(inventoryData);
      setSelectedItems(prev => {
        const map = new Map();
        prev.forEach(si => map.set(si.id, si));
        allVisible.forEach(item => {
          if (!map.has(item.id)) {
            map.set(item.id, {
              id: item.id,
              name: item.name,
              qty: 1,
              days: item.days,
              price: item.price,
              vendor: item.vendor,
              available: item.available,
            });
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

  const handleAttachmentChange = (e) => {
    const files = Array.from(e.target.files);
    setAttachments(prev => [...prev, ...files]);
  };

  const removeAttachment = (index) => {
    setAttachments(prev => prev.filter((_, i) => i !== index));
  };

  const handleGeneratePdf = async () => {
    setIsGeneratingPdf(true);
    setErrorMessage(null); // NEW: Clear previous errors

    try {
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
        packupDate
      };

      // NEW: Await generateQuotationPdf and get filePath and pdfBlob
      const { filePath, pdfBlob } = await generateQuotationPdf(quotationData);

      // NEW: Trigger client-side download using pdfBlob
      const url = window.URL.createObjectURL(pdfBlob);
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `quotation_${quotationData.customerName}.pdf`);
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.URL.revokeObjectURL(url);

      // NEW: Pass filePath to saveQuotationData
      await saveQuotationData(quotationData, filePath);
    } catch (error) {
      console.error('Error generating or saving PDF:', error);
      // NEW: Set error message for user feedback
      setErrorMessage('Failed to generate or save PDF. Please try again.');
    } finally {
      setIsGeneratingPdf(false);
    }
  };

  const saveQuotationData = async (quotationData, filePath) => {
    try {
      console.log('Saving with customerId:', selectedCustomer?.id, 'and filePath:', filePath);
      const response = await axios.post('/api/v1/quotations/saveall', {
        ...quotationData,
        customerId: selectedCustomer?.id || null,
        status: 'draft',
        pdfPath: filePath, // NEW: Include filePath in the request
        setupDate: quotationData.setupDate,
        packupDate: quotationData.packupDate,
      });
      
      console.log('Quotation saved:', response.data);
      fetchQuotations(); // Refresh list after save
      return response.data;
    } catch (error) {
      console.error('Error saving quotation:', error);
      throw error;
    }
  };

  const handleFrontPageChange = (field, value) => {
    setFrontPage(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleBackPageChange = (field, value) => {
    setBackPage(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const toggleCustomization = () => {
    setCustomizationOpen(!customizationOpen);
  };

  const handleEditQuotation = (quotation) => {
    setEditingQuotation(quotation);
  };

  const handleDeleteQuotation = async (id) => {
    if (window.confirm('Are you sure you want to delete this quotation?')) {
      try {
        await axios.delete(`/api/v1/quotations/delete/${id}`);
        fetchQuotations();
      } catch (error) {
        console.error('Error deleting quotation:', error);
      }
    }
  };

  const selectedCount = selectedItems.length;

  const QuotationList = () => {
    const filteredQuotations = quotations.filter(quote =>
      quote.customer_name.toLowerCase().includes(searchQuoteTerm.toLowerCase()) ||
      quote.project_name?.toLowerCase().includes(searchQuoteTerm.toLowerCase())
    );
    console.log("this is quote",filteredQuotations);

    return (
      <div className="dashboard-card fade-in mt-4">
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
                  onChange={(e) => setSearchQuoteTerm(e.target.value)}
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
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {filteredQuotations.map((quote) => (
                  <tr key={quote.id}>
                    <td>{quote.quotation_number}</td>
                    <td>{quote.customer_name}</td>
                    <td>{quote.project_name || '-'}</td>
                    <td>${quote.total_amount.toLocaleString()}</td>
                    <td>{quote.date}</td>
                    <td>
                      <span className={`badge ${
                        quote.status === 'approved' ? 'bg-success' :
                        quote.status === 'pending' ? 'bg-warning' :
                        quote.status === 'rejected' ? 'bg-danger' : 'bg-secondary'
                      }`}>
                        {quote.status.charAt(0).toUpperCase() + quote.status.slice(1)}
                      </span>
                    </td>
                    <td>
                      <div className="d-flex gap-2">
                        <button 
                          className="btn btn-sm btn-outline-primary"
                          onClick={() => handleEditQuotation(quote)}
                        >
                          <i className="fas fa-edit"></i>
                        </button>
                        <button 
                          className="btn btn-sm btn-outline-danger"
                          onClick={() => handleDeleteQuotation(quote.id)}
                        >
                          <i className="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    );
  };

  return (
    <div className="dashboard-container">
      <div className="main-content">
        <div className="header fade-in">
          <ul className="nav nav-tabs header-tabs">
            <li className="nav-item">
              <button
                className={`nav-link ${activePage === 'create' ? 'active' : ''}`}
                onClick={() => setActivePage('create')}
              >
                <i className="fas fa-file-invoice me-2"></i> Create New Quotation
              </button>
            </li>
            <li className="nav-item">
              <button
                className={`nav-link ${activePage === 'list' ? 'active' : ''}`}
                onClick={() => setActivePage('list')}
              >
                <i className="fas fa-list me-2"></i> Quotation List
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

        {activePage === 'create' ? (
          <>
            {/* NEW: Display error message if exists */}
            {errorMessage && (
              <div className="alert alert-danger mt-4 fade-in" role="alert">
                {errorMessage}
                <button
                  type="button"
                  className="btn-close"
                  onClick={() => setErrorMessage(null)}
                  aria-label="Close"
                ></button>
              </div>
            )}

            <div className="stats-summary">
              <div className="row fade-in delay-1 mt-4">
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
                          <h3 className="mb-0">{quotations.filter(q => q.status === 'approved').length}</h3>
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
                          <h3 className="mb-0">{quotations.filter(q => q.status === 'pending').length}</h3>
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
                          <h3 className="mb-0">{quotations.filter(q => q.status === 'rejected').length}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="dashboard-card fade-in delay-2 mt-4">
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
                      {selectedCount} item{selectedCount !== 1 ? "s" : ""}
                    </span>
                  </button>
                  {editingQuotation && (
                    <button
                      className="btn btn-outline-danger ms-2"
                      onClick={() => {
                        setEditingQuotation(null);
                        clearCustomerSelection();
                      }}
                    >
                      Cancel Edit
                    </button>
                  )}
                </li>
              </ul>

              <div className="tab-content p-3">
                <div className={`tab-pane fade ${activeTab === 'details' ? 'show active' : ''}`}>
                  <div
                    className="row g-4 p-3 rounded bg-white"
                    style={{ boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)' }}
                  >
                    <div className="col-md-4 position-relative">
                      <label className="form-label" style={{ fontWeight: 600 }}>Customer <span style={{ color: 'red' }}>*</span></label>
                      <div className="input-group" style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.2)' }}>
                        <input
                          type="text"
                          className="form-control border-primary-subtle"
                          placeholder="Search customers..."
                          value={customerSearchTerm}
                          onChange={(e) => setCustomerSearchTerm(e.target.value)}
                          style={{ borderRight: 'none' }}
                        />
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
                        <div
                          className="customer-dropdown position-absolute z-10 bg-white mt-1 w-100"
                        >
                          {filteredCustomers.map(customer => (
                            <div
                              key={customer.id}
                              className="p-2 border-bottom"
                              onClick={() => handleCustomerSelect(customer)}
                              style={{
                                cursor: 'pointer',
                                backgroundColor: '#fff',
                                transition: 'background-color 0.2s',
                              }}
                              onMouseOver={(e) => e.currentTarget.style.backgroundColor = '#f8f9fa'}
                              onMouseOut={(e) => e.currentTarget.style.backgroundColor = '#fff'}
                            >
                              <div className="fw-bold">{customer.name}</div>
                              <small classFam1>{customer.contactPerson} • {customer.type}</small>
                            </div>
                          ))}
                        </div>
                      )}
                    </div>

                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>Estimate Type <span style={{ color: 'red' }}>*</span></label>
                      <select className="form-select border-primary-subtle" required style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}>
                        <option value="">Select Type</option>
                        <option>Project</option>
                        <option>Service</option>
                        <option>Product</option>
                      </select>
                    </div>

                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>Customer Type <span style={{ color: 'red' }}>*</span></label>
                      <select
                        className="form-select border-primary-subtle"
                        value={selectedCustomerType ?? ""}
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
                      <label className="form-label" style={{ fontWeight: 600 }}>Contact Person</label>
                      <input
                        type="text"
                        className="form-control border-secondary-subtle"
                        value={selectedCustomer?.contactPerson ?? ""}
                        readOnly
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-6">
                      <label className="form-label" style={{ fontWeight: 600 }}>Project Name <span style={{ color: 'red' }}>*</span></label>
                      <input
                        type="text"
                        className="form-control border-secondary-subtle"
                        value={projectName ?? ""}
                        onChange={(e) => setProjectName(e.target.value)}
                        required
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>Phone No</label>
                      <input
                        type="tel"
                        className="form-control"
                        value={phone ?? ""}
                        onChange={(e) => setPhone(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>Client Email</label>
                      <input
                        type="email"
                        className="form-control"
                        value={email ?? ""}
                        onChange={(e) => setEmail(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-4">
                      <label className="form-label" style={{ fontWeight: 600 }}>Date <span style={{ color: 'red' }}>*</span></label>
                      <input
                        type="date"
                        className="form-control"
                        defaultValue={new Date().toISOString().split('T')[0]}
                        required
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Estimate No <span style={{ color: 'red' }}>*</span></label>
                      <input
                        type="text"
                        className="form-control"
                        defaultValue={`EST-${Math.floor(Math.random() * 10000)}`}
                        readOnly
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Location</label>
                      <input
                        type="text"
                        className="form-control"
                        value={location ?? ""}
                        onChange={(e) => setLocation(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Venue</label>
                      <input
                        type="text"
                        className="form-control"
                        defaultValue="Main Office"
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Sales Person</label>
                      <input
                        type="text"
                        className="form-control"
                        defaultValue="Alex Morgan"
                        readOnly
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Status</label>
                      <select className="form-select border-warning-subtle" style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                      </select>
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Start Date</label>
                      <input
                        type="date"
                        className="form-control"
                        defaultValue={new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>End Date</label>
                      <input
                        type="date"
                        className="form-control"
                        defaultValue={new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>
                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Set up Date</label>
                      <input
                        type="date"
                        className="form-control"
                        value={setupDate}
                        onChange={(e) => setSetupDate(e.target.value)}
                        style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.3)' }}
                      />
                    </div>

                    <div className="col-md-3">
                      <label className="form-label" style={{ fontWeight: 600 }}>Pack up Date</label>
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

                <div className={`tab-pane fade ${activeTab === 'inventory' ? 'show active' : ''}`}>
                  <div className="search-container mb-3 d-flex align-items-center gap-2">
                    <div className="search-box d-flex align-items-center">
                      <i className="fas fa-search me-2"></i>
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
                      style={{ maxWidth: "200px" }}
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
                              items.forEach(item => {
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
                            return allIds.every(id => selectedItems.some(si => si.id === id));
                          })()
                        }
                      />
                      <label className="form-check-label">
                        Select All
                      </label>
                    </div>
                  </div>

                  <div className="table-responsive">
                    <table className="table tree-table table-hover">
                      <thead className="table-light">
                        <tr>
                          <th style={{ width: "40px" }}></th>
                          <th>Inventory Item</th>
                          <th>Available</th>
                          <th>Quantity</th>
                          <th>Days</th>
                          <th>Vendor</th>
                          <th>Unit Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        {renderInventoryRows(inventoryData)}
                      </tbody>
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

                <div className={`tab-pane fade ${activeTab === 'selected' ? 'show active' : ''}`}>
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
                                {selectedItems.map(si => {
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
                                          value={si.qty ?? 1}
                                          onChange={(e) => handleQtyChangeSelected(si.id, e.target.value)}
                                          style={{ maxWidth: "80px" }}
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
                            <label className="form-check-label" for="includeSetup">
                              Include setup fee ($150)
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
                            <label className="form-check-label" for="expressShipping">
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
                                        value={frontPage.title ?? ""}
                                        onChange={(e) => handleFrontPageChange('title', e.target.value)}
                                      />
                                    </div>
                                    <div className="mb-3">
                                      <label className="form-label">Content</label>
                                      <textarea 
                                        className="form-control" 
                                        rows="3"
                                        value={frontPage.content ?? ""}
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
                                        value={backPage.title ?? ""}
                                        onChange={(e) => handleBackPageChange('title', e.target.value)}
                                      />
                                    </div>
                                    <div className="mb-3">
                                      <label className="form-label">Content</label>
                                      <textarea 
                                        className="form-control" 
                                        rows="3"
                                        value={backPage.content ?? ""}
                                        onChange={(e) => handleBackPageChange('content', e.target.value)}
                                      />
                                    </div>
                                  </div>
                                  <div className="col-md-12">
                                    <h5>Attachments</h5>
                                    <input 
                                      type="file" 
                                      multiple 
                                      onChange={handleAttachmentChange}
                                      className="form-control mb-2" 
                                    />
                                    <div className="attachments-list">
                                      {attachments.map((file, index) => (
                                        <div key={index} className="d-flex justify-content-between align-items-center mb-1">
                                          <span className="text-truncate" style={{maxWidth: '200px'}}>
                                            {file.name}
                                          </span>
                                          <button 
                                            className="btn btn-sm btn-danger"
                                            onClick={() => removeAttachment(index)}
                                          >
                                            <i className="fas fa-trash"></i>
                                          </button>
                                        </div>
                                      ))}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div className="d-grid mt-4">
                          <button 
                            className="btn btn-light btn-lg fw-bold"
                            onClick={handleGeneratePdf}
                            disabled={isGeneratingPdf || selectedItems.length === 0}
                          >
                            {isGeneratingPdf ? (
                              <>
                                <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Generating...
                              </>
                            ) : (
                              <>
                                <i className="fas fa-file-pdf me-2"></i> Generate Quotation
                              </>
                            )}
                          </button>
                        </div>
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
                  </div>
                </div>
              </div>
            </div>
          </>
        ) : (
          <QuotationList />
        )}
      </div>
    </div>
  );
}

export default Quotation;