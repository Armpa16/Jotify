# Jotify

![Jotify Banner](img/Jotify.png)

> **Jotify** — สมุดจดโน้ตและจัดการงาน สวยงาม ใช้งานง่าย 

---

## ✨ ฟีเจอร์เด่น (Features)

- **จดโน้ตและจัดการงาน (Note & Task Management):**  
  เพิ่ม แก้ไข ลบโน้ต/งานอย่างรวดเร็ว พร้อมระบุวันครบกำหนดและรายละเอียด
- **Dashboard & สถานะงาน:**  
  แยกหน้ารวมงาน (Home), งานใกล้ครบกำหนด (Near Death), งานที่เสร็จแล้ว (Done), ถังขยะ (Trash)
- **ปฏิทิน (Calendar):**  
  ดูงานตามวันเดือนปีด้วยปฏิทินแบบอินเทอร์แอคทีฟ
- **แจ้งเตือน (Notification):**  
  ระบบแจ้งเตือนงานใกล้ถึงกำหนด(ผ่าน Gamil)
- **ระบบผู้ใช้ (User System):**  
  ลงทะเบียน/เข้าสู่ระบบ รองรับ Google OAuth
- **UI/UX สวยทันสมัย:**  
  ใช้ Bootstrap, FontAwesome, CSS เฉพาะตัว

---

## 🏗️ โครงสร้างโปรเจค

```
Jotify/
│
├── calendar.php           # หน้าปฏิทิน
├── home.php               # หน้ารวมงาน
├── neardeath.php          # งานใกล้ครบกำหนด
├── done.php               # งานที่เสร็จแล้ว
├── trash.php              # ถังขยะ
├── loginform.php          # ฟอร์มล็อกอิน
├── register.php           # ลงทะเบียน
├── send_notification.php  # ระบบแจ้งเตือน
│
├── css/                   # ไฟล์ CSS
├── img/                   # โลโก้และรูปภาพ
├── connect+processDB/     # ฐานข้อมูล/สคริปต์เชื่อมต่อ
├── todolist.sql           # สร้างฐานข้อมูล
├── composer.json|lock     # จัดการ dependency
├── vendor/                # ไฟล์ dependency (Composer)
```

---

## ⚙️ เทคโนโลยีที่ใช้

- **Backend:** PHP (MySQLi), Session, RESTful PHP
- **Frontend:** HTML5, CSS3, JavaScript, jQuery, Bootstrap 5, FontAwesome 6, SweetAlert2
- **Database:** MySQL (`todolist.sql`)
- **OAuth:** Google Login

---

## 🚀 วิธีติดตั้งและใช้งาน

1. **Clone Repo**
    ```sh
    git clone https://github.com/Armpa16/Jotify.git
    cd Jotify
    ```

2. **ติดตั้ง Dependency (ถ้ามี)**
    ```sh
    composer install
    ```

3. **นำเข้า Database**
    - สร้างฐานข้อมูล MySQL ชื่อ `todolist`
    - นำเข้าไฟล์ `todolist.sql`

4. **ตั้งค่าเชื่อมต่อฐานข้อมูล**
    - แก้ไข host, user, password ในไฟล์ `home.php`, `calendar.php`, `done.php` ฯลฯ (ค่า default: `root` ไม่มีรหัสผ่าน)

5. **รันบนเซิร์ฟเวอร์ที่รองรับ PHP**  
   เช่น XAMPP, MAMP หรือเซิร์ฟเวอร์จริง

6. **ใช้งานผ่านเว็บเบราว์เซอร์**
    - เปิด `http://localhost/Jotify/loginform.php`  
    - สมัครสมาชิก / ล็อกอิน แล้วเริ่มใช้งานได้ทันที!

---

## 🖼️ ตัวอย่างหน้าจอ (Screenshots)

---

### <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/icons/house-door-fill.svg" alt="Home Icon" style="height:1.4em;vertical-align:middle;"> Jotify Dashboard

> สรุปงานและโน้ตทั้งหมดในที่เดียว  
> จัดหมวดหมู่ แทรกงานใหม่ แก้ไข/ลบ ได้อย่างรวดเร็ว

![Jotify Dashboard](https://github.com/user-attachments/assets/907e513c-50e9-49ae-8786-76d04a5ede5f)

---

### <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/icons/calendar-check.svg" alt="Calendar Icon" style="height:1.2em;vertical-align:middle;"> Jotify Calendar

> ปฏิทินอินเทอร์แอคทีฟ  
> ดูกำหนดการและโน้ตในแต่ละวันได้อย่างชัดเจน

![Jotify Calendar](https://github.com/user-attachments/assets/2915222b-5e63-4857-93c5-bd846717d1f3)

---

## 💡 Tips & Highlights

- เพิ่มงานใหม่ได้ทันทีผ่านปุ่มวงกลม “+” มุมซ้าย
- กด “แก้ไข” หรือ “ลบ” ที่โน้ตแต่ละรายการ
- ระบบแจ้งเตือนงานใกล้ครบกำหนดช่วยให้ไม่พลาด
- รองรับการแนบรูปภาพกับโน้ต
- ถังขยะสามารถกู้คืนโน้ตหรือจะลบทิ้งถาวร

---

## ⭐ ช่วยกด Star หรือ Fork หากชอบโปรเจคนี้นะครับ!

---

> **Jotify — จดโน้ต จัดการงาน ให้ชีวิตง่ายขึ้น!**

---

**หมายเหตุ:**  
- ผลลัพธ์นี้อาจไม่ครอบคลุมไฟล์ทั้งหมด ดูไฟล์เพิ่มเติมได้ที่ [Armpa16/Jotify (GitHub Repo)](https://github.com/Armpa16/Jotify/tree/main)
