CALL / CallAI — Setup guide (Hindi)
==================================

IMPORTANT:
Yeh complete starter project hai, lekin production-ready final app nahi.
Ismein account creation/login, unique Call ID, PHP/MySQL backend, basic WebRTC
call UI aur PHP polling-based signaling ka starter implementation hai.
AI health-check placeholder hai; actual face-emotion model aur full sign-language
translation abhi included nahi. WebRTC calling ko two-device/network testing aur
TURN configuration ki zaroorat ho sakti hai.

A. XAMPP par setup
1. XAMPP install karein (Apache + MySQL).
2. ZIP extract karein. "call" folder ko C:\\xampp\\htdocs\\call mein rakhein.
3. XAMPP Control Panel se Apache aur MySQL start karein.
4. Browser mein http://localhost/phpmyadmin kholein.
5. Import tab mein database.sql import karein.
6. config.php mein DB_HOST/DB_NAME/DB_USER/DB_PASS apne MySQL ke mutabik check karein.
7. Browser mein http://localhost/call/ kholein.
8. "Account banayein" tab se account banayein. Doosre user ke liye alag browser/profile
   ya doosre device par doosra account banayein.

B. Call testing
- Dono accounts database mein hone chahiye.
- Caller dost ki Call ID daale aur Video Call dabaye.
- Callee ko app page khula rakhna hoga aur incoming call poll hoti rahegi.
- Camera/microphone permissions allow karein. localhost par browser permissions supported hain.
- WebRTC STUN server included hai, lekin kuch networks/NATs mein TURN server ki zaroorat hogi.
- Agar call connect na ho, pehle same laptop par do browser profiles ya same Wi-Fi ke do devices
  par test karein; browser console aur PHP/MySQL errors check karein.

C. Python helper (optional)
1. Python 3.10+ install karein.
2. Command Prompt mein:
   cd C:\\xampp\\htdocs\\call\\python-ai
   python -m venv .venv
   .venv\\Scripts\\activate
   pip install -r requirements.txt
   python app.py
3. Browser mein app ka "AI service check karein" button dabayein.
   Is waqt yeh sirf service status hai, live emotion/gesture recognition nahi.

D. Online hosting
- GitHub par code store kar sakte hain; GitHub Pages PHP/Python backend nahi chalata.
- Internet par use karne ke liye PHP + MySQL hosting, HTTPS, secure session settings,
  signaling service, and usually STUN/TURN configuration chahiye.
- XAMPP development server ko seedha public internet par expose na karein.
- DB credentials, HTTPS, rate limiting, access control, privacy/consent and data retention
  ko production se pehle secure karein.

E. Features and limitations
Included:
- Registration/login with password_hash/password_verify
- Unique public Call ID
- Responsive dark UI
- Basic WebRTC signaling endpoints via PHP/MySQL polling
- Camera/mic toggles, accept/decline/end controls
- Python service health check

Not yet implemented:
- Robust multi-device call state management / WebSocket signaling
- TURN server credentials
- Real AI face emotion inference
- Real sign-language translation / gesture model
- Push notifications, contacts list, password reset, email verification
- Production security audit, load testing, mobile APK wrapper

Privacy: camera/microphone activate only after user action and browser permission.
Expressions are uncertain estimates, not proof of what someone feels. Gesture/sign-language
recognition needs explicit consent and proper model training/testing.
