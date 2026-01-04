# HireFlow HRMS 🚀

HireFlow is a PHP-based Recruitment and Offer Letter Automation System designed to digitize and streamline the hiring process. It connects candidate data, approval workflows, and automated offer letter generation into a single, secure platform.

---

## 📌 About

HireFlow digitizes the hiring process by connecting candidate data, approval workflows, and automated offer letter delivery into a single system. It minimizes delays, reduces manual errors, and ensures consistency across all hiring communications.

---

## ✨ Key Features

- Candidate Registration & Management  
- Admin / Manager Role-Based Access  
- Interview & Approval Workflow  
- Salary & Joining Date Validation  
- Automated Offer Letter (PDF) Generation  
- Email Delivery with Attachment  
- Secure Offer Download Link (Token-Based)  
- Audit-Friendly Records & Logs  

---

## 🛠 Technology Stack

| Layer       | Technology |
|------------|-----------|
| Backend     | PHP 8.x |
| Database    | MySQL |
| Frontend    | HTML, Bootstrap |
| PDF Engine  | mPDF |
| Email       | PHPMailer (SMTP) |
| Server      | Apache (XAMPP) |

---

## 📂 Project Structure

HireFlow
├── public/
│   ├── login.php
│   ├── dashboard.php
│   └── offer_generate.php
├── templates/
│   └── offer_letter_template.php
├── config/
│   ├── auth.php
│   ├── db.example.php
│   └── mail.example.php
├── sql/
│   ├── schema.sql
│   └── seed_admin.php
├── composer.json
├── composer.lock
├── .gitignore
└── README.md



Login using the generated admin credentials.

---

## 📄 Offer Letter Flow

1. Candidate added
2. Interview conducted
3. Status set to **APPROVED**
4. Salary & Joining Date filled
5. Offer Letter generated (PDF)
6. Email sent automatically with attachment
7. Secure download link provided

---

## 🔒 Security Notes

- Sensitive files are excluded using `.gitignore`
- Database & SMTP credentials are never committed
- Token-based secure offer downloads
- Role-based access control

---

## 📈 Use Cases

- HR Departments
- Recruitment Agencies
- Startups & SMEs
- Internal Hiring Automation
- Campus Placement Management

---

## 📜 License

This project is intended for **internal HR automation and learning purposes**.  
Not licensed for public redistribution without permission.

---

## 👨‍💻 Author

**Yogesh Kumar**  
GitHub: https://github.com/YogeshKumar805  

---

⭐ If you find this project useful, consider giving it a star!
