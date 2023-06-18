<!DOCTYPE html>
<html>
  <title>Generate Certificate</title>
  <script src="js/pdf-lib.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.min.js"></script> -->
  <script>
    const textboxWidth = 340;
    const textboxHeight = 100;
    const fontSize = 32.5;
    const yPositionPercent = 62;

    async function generateCertificate(fullName) {
      const { PDFDocument, degrees, rgb } = PDFLib;
      const imageUrl = 'img/coa.jpg';
      const imageBytes = await fetch(imageUrl).then((res) => res.arrayBuffer());
      const pdfDoc = await PDFDocument.create();
      const page = pdfDoc.addPage();
      const { width, height } = page.getSize();
      const image = await pdfDoc.embedJpg(imageBytes);
      page.drawImage(image, {
        x: 0,
        y: 0,
        width: width,
        height: height,
      });

      const yPosition = (height * yPositionPercent) / 100;
      const textX = (width - textboxWidth) / 2;
      const textY = yPosition + (textboxHeight - fontSize) / 2;

      const textOptions = {
        x: textX,
        y: textY,
        size: fontSize,
        color: rgb(0.078, 0.451, 0.231), // Updated color value
        font: await pdfDoc.embedFont('Helvetica-Bold'),
        opacity: 1,
        maxWidth: textboxWidth,
        maxHeight: textboxHeight,
        lineHeight: fontSize,
        align: 'center',
      };

      const pageTextBox = page.drawText(fullName, textOptions);

      const pdfBytes = await pdfDoc.save();
      downloadPDF(pdfBytes, 'certificate_' + fullName + '.pdf');
    }

    function downloadPDF(pdfBytes, fileName) {
      const blob = new Blob([pdfBytes], { type: 'application/pdf' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = fileName;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    var url = new URL(window.location.href);
    var params = new URLSearchParams(url.search);
    var name = params.get('name');
    if (name) {
      generateCertificate(name);
    }
  </script>
</html>
