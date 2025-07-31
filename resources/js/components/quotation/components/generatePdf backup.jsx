import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';  // Named import

export const generateQuotationPdf = (quotationData, frontPageContent, backPageContent) => {
  const doc = new jsPDF();
  
  // Front Page
  if (frontPageContent) {
    doc.setFontSize(22);
    doc.text(frontPageContent.title || 'QUOTATION', 105, 40, { align: 'center' });
    doc.setFontSize(12);
    
    const frontContentLines = doc.splitTextToSize(
      frontPageContent.content || `Prepared for: ${quotationData.customerName}`,
      180
    );
    
    let yPosition = 60;
    frontContentLines.forEach(line => {
      doc.text(line, 105, yPosition, { align: 'center' });
      yPosition += 7;
    });
    doc.addPage();
  }

  // Main Content
  doc.setFontSize(18);
  doc.text('QUOTATION DETAILS', 105, 20, { align: 'center' });
  
  doc.setFontSize(12);
  doc.text(`Customer: ${quotationData.customerName}`, 15, 30);
  doc.text(`Project: ${quotationData.projectName}`, 15, 40);
  doc.text(`Date: ${quotationData.date}`, 15, 50);
  
  // Use autoTable explicitly
  autoTable(doc, {
    startY: 60,
    head: [['Item', 'Qty', 'Unit Price', 'Total']],
    body: quotationData.items.map(item => [
      item.name,
      item.qty,
      `$${item.price.toFixed(2)}`,
      `$${(item.qty * item.price).toFixed(2)}`
    ]),
    styles: { fontSize: 10 },
    headStyles: { fillColor: [41, 128, 185] }
  });
  
  // Summary
  const finalY = doc.lastAutoTable.finalY + 10;
  doc.text(`Subtotal: $${quotationData.subtotal.toFixed(2)}`, 150, finalY);
  doc.text(`Discount: $${quotationData.discount.toFixed(2)}`, 150, finalY + 10);
  doc.text(`Tax: $${quotationData.tax.toFixed(2)}`, 150, finalY + 20);
  doc.text(`Total: $${quotationData.totalAmount.toFixed(2)}`, 150, finalY + 30);
  
  // Back Page
  if (backPageContent) {
    doc.addPage();
    doc.setFontSize(16);
    doc.text(backPageContent.title || 'TERMS & CONDITIONS', 105, 30, { align: 'center' });
    doc.setFontSize(10);
    
    const backContentLines = doc.splitTextToSize(
      backPageContent.content || 'Standard terms and conditions apply.',
      180
    );
    
    let yPosition = 50;
    backContentLines.forEach(line => {
      doc.text(line, 15, yPosition);
      yPosition += 7;
    });
  }
  
  return doc;
};