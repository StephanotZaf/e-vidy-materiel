function generatePDF(){
    const element = document.getElementById("facture");

    html2pdf()
    .from(element)
    .save();
}