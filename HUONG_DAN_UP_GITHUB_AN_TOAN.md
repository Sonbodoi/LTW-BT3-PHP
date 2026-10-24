# HUONG DAN DAY SOURCE UNIVERSAL TEA LEN GITHUB

Repository do ban cung cap: https://github.com/Sonbodoi/BT3---LTW-PHP

## 1. Quan trong: bao mat
- GitHub trong anh cho thay `.env` da duoc upload. Phai xoa file do khoi GitHub.
- Khong upload `.env`, `database/database.sqlite`, `vendor/`, `node_modules/`, `storage/logs/`, `public/build/`.
- `.env.example` la file mau va CAN duoc upload. Cac mat khau demo trong code chi phuc vu local.
- Neu `.env` tung duoc cong khai, can doi APP_KEY/khóa truy cap da lo. Xoa file o commit hien tai khong xoa lich su Git.
- Neu tai khoan GitHub chua co quyen ghi repository, can duoc cap quyen truoc khi push.

## 2. Cach upload on dinh bang GitHub Desktop
1. Cai GitHub Desktop: https://desktop.github.com/ va dang nhap tai khoan co quyen sua repository.
2. File > Clone Repository > URL > https://github.com/Sonbodoi/BT3---LTW-PHP
3. Giai nen ZIP nay, mo thu muc `BTB-3-PHP-main`, COPY TAT CA noi dung trong do vao thu muc repository vua clone.
   **Chi copy noi dung BEN TRONG**, khong chep ca thu muc `BTB-3-PHP-main` vao repository.
   Khong ghi de/xoa thu muc `.git` ben trong repository.
4. Xoa file `.env` da upload trong thu muc repository (neu co) de GitHub Desktop ghi nhan mot thay doi xoa file. Truoc khi xoa, sao luu file cau hinh tren may neu can.
5. GitHub Desktop > Changes: kiem tra danh sach thay doi, `.env` phai hien la da xoa, `.env.example` duoc giu.
6. Commit to main > Push origin (hoac tao branch/PR neu repository yeu cau review).

## 3. Cach upload qua Git CMD neu da cai Git
Mo CMD o thu muc bat ky, nhap:

    git clone https://github.com/Sonbodoi/BT3---LTW-PHP.git
    cd BT3---LTW-PHP

Sao chep noi dung `BTB-3-PHP-main` trong ZIP nay vao thu muc `BT3---LTW-PHP`.
Neu `.env` dang duoc Git theo doi:

    git rm --cached .env

Sau do:

    git add -A
    git status
    git commit -m "Complete Universal Tea product management source"
    git push origin main

Neu branch khong phai `main`, thay bang ten branch thuc te. Neu Git bao xung dot, KHONG force push.

## 4. Thu muc can co o goc repository
- app/, bootstrap/, config/, database/, public/, resources/, routes/, storage/, tests/
- artisan, composer.json, composer.lock, package.json, package-lock.json, vite.config.js
- .env.example, .gitignore, README.md, CAI_DAT_LAN_DAU.cmd, CHAY_WEB.cmd
- scripts/, HUONG_DAN_CHAY_WINDOWS.md, TAI_ANH_THUC_TE.cmd

## 5. Kiem tra sau khi clone (tren Windows)
CMD vao thu muc repository va chay:

    composer install
    npm install
    copy .env.example .env
    php artisan key:generate
    if not exist database\database.sqlite type nul > database\database.sqlite
    php artisan migrate --seed
    php artisan storage:link
    npm run build
    php artisan serve

Mo: http://127.0.0.1:8000/login
Admin: admin@universaltea.test / password
Khach: test@example.com / password

## 6. Anh chup that
ZIP nay co 30 anh minh hoa SVG. 29 anh chup that da tai tren PC cua ban KHONG nam trong ZIP nen ban co the:
- Cho vao `public/images/menu/real` tren may cua ban va commit len GitHub neu phu hop giay phep; HOAC
- Sau khi clone, chay `TAI_ANH_THUC_TE.cmd` de thu tai tu Pexels. Mot so anh co the khong tai duoc va tu dong fallback SVG.

Luu y: GitHub luu ma nguon, khong tu dong host PHP Laravel va SQLite. Muon co duong link web truy cap tu Internet can trien khai tren hosting/VPS ho tro PHP.
