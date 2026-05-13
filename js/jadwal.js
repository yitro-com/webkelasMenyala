function tampilkanJadwal() {

    const kelas =
        document.getElementById("kelasSelect").value;

    const jadwalContainer =
        document.getElementById("jadwalContainer");

    const isiJadwal =
        document.getElementById("isiJadwal");

    const judulKelas =
        document.getElementById("judulKelas");

    const dataJadwal = {

        // DATA KELAS
        I: [
            ["K4253-T", "ANALISIS DATA SPASIAL", "4TSDA-I", "SENIN", "11.10-12.50", "B106", "Usman SE.,M.Kom"],
            ["K4253-T", "ANALISIS DATA SPASIAL", "4TSDA-9", "JUMAT", "09.20-11.00", "A306", "Usman SE.,M.Kom"],
            ["M4223-T", "STATISTIK DAN PROBABILITAS", "4TSPB-I", "SENIN", "13.40-15.20", "B104", "Dr. Husain T ST., MT., M.Pd."],
            ["M4223-T", "STATISTIK DAN PROBABILITAS", "4TSPB-9", "JUMAT", "13.40-15.20", "B105", "Dr. Husain T ST., MT., M.Pd."],
            ["K4221-T", "PRAKTIKUM PEMROGRAMAN APLIKASI MOBILE NATIVE", "4TLMN-I", "SELASA", "07.30-09.10", "A306", "Rahmat S.Kom., M.Kom"],
            ["K3762-T", "KECERDASAN BUATAN", "4TPIB-I", "SELASA", "11.10-12.50", "B212", "Suryani S.Kom., MT."],
            ["K4224-T", "ANALISIS DAN DESAIN SISTEM (OBJEK-UML)", "4TADS-I", "SELASA", "15.40-17.20", "B103", "Madyana Patasik S.Kom.,M.T"],
            ["K4224-T", "ANALISIS DAN DESAIN SISTEM (OBJEK-UML)", "4TADS-9", "KAMIS", "15.40-17.20", "B103", "Madyana Patasik S.Kom.,M.T"],
            ["K2112-T", "PENGANTAR FORENSIK TEKNOLOGI INFORMASI", "4TFOR-I", "RABU", "07.30-09.10", "A110", "Sri Wahyuni S.Kom.,MT"],
            ["M5032-T", "MATEMATIKA DISKRIT", "4TDIS-I", "RABU", "09.20-11.00", "A111", "Asrul Syam S.Si.,M.Si"],
            ["K4212-T", "PEMROGRAMAN APLIKASI MOBILE NATIVE", "4TPMN-I", "RABU", "15.40-17.20", "A111", "Khaerunnisa Hanapi S.Kom., M.Kom"],
            ["J4222-T", "ANALISIS JARINGAN KOMPUTER", "4TJKL-I", "KAMIS", "07.30-09.10", "B211", "Asmah Akhriana ST., MT."],
            ["U4222-T", "HUKUM TELEMATIKA", "4THTL-I", "KAMIS", "09.20-11.00", "B102", "Erni Marlina S.Kom., M.I.Kom"]
        ],

        F: [
            ["M5032-T", "MATEMATIKA DISKRIT", "4TDIS-F", "SENIN", "07.30-09.10", "B213", "Asrul Syam S.Si., M.Si"],
            ["K4221-T", "PRAKTIKUM PEMROGRAMAN APLIKASI MOBILE NATIVE", "4TLMN-F", "SENIN", "09.20-11.00", "A306", "Arham Arifin S.KOM., MT"],
            ["K3762-T", "KECERDASAN BUATAN", "4TPIB-F", "SENIN", "11.10-12.50", "B101", "Suryani S.Kom., MT"],
            ["K2112-T", "PENGANTAR FORENSIK TEKNOLOGI INFORMASI", "4TFOR-F", "SENIN", "13.40-15.20", "B214", "Michael Oktavianus S.Kom., MM"],
            ["K4224-T", "ANALISIS DAN DESAIN SISTEM (OBJEK-UML)", "4TADS-F", "SELASA", "09.20-11.00", "B103", "Madyana Patasik S.Kom., M.T"],
            ["K4253-T", "ANALISIS DATA SPASIAL", "4TSDA-F", "SELASA", "11.10-12.50", "B105", "Dr. Cucut Susanto S.Kom., M.Si"],
            ["M4223-T", "STATISTIK DAN PROBABILITAS", "4TSPB-F", "SELASA", "13.40-15.20", "B210", "Dr. Husain T ST., MT., M.Pd"],
            ["J4222-T", "ANALISIS JARINGAN KOMPUTER", "4TJKL-F", "RABU", "09.20-11.00", "B211", "Muhardi S.Kom., MT"],
            ["U4222-T", "HUKUM TELEMATIKA", "4THTL-F", "RABU", "11.10-12.50", "B102", "Rudy Donny Likliwatil SE., M.Kom"],
            ["K4212-T", "PEMROGRAMAN APLIKASI MOBILE NATIVE", "4TPMN-F", "RABU", "13.40-15.20", "B209", "Khaerunnisa Hanapi S.Kom., M.Kom"],
            ["K4224-T", "ANALISIS DAN DESAIN SISTEM (OBJEK-UML)", "4TADS-6", "KAMIS", "09.20-11.00", "B103", "Madyana Patasik S.Kom., M.T"],
            ["K4253-T", "ANALISIS DATA SPASIAL", "4TSDA-6", "KAMIS", "11.10-12.50", "B105", "Dr. Cucut Susanto S.Kom., M.Si"],
            ["M4223-T", "STATISTIK DAN PROBABILITAS", "4TSPB-6", "KAMIS", "13.40-15.20", "B210", "Dr. Husain T ST., MT., M.Pd"]
        ],

        E: [
            ["K4221-T", "PRAKTIKUM PEMROGRAMAN APLIKASI MOBILE NATIVE", "4TLMN-E", "SENIN", "07.30-09.10", "A306", "Khaerunnisa Hanapi S.Kom., M.Kom"],
            ["K3762-T", "KECERDASAN BUATAN", "4TPIB-E", "SENIN", "09.20-11.00", "B106", "Suryani S.Kom., MT."],
            ["K2112-T", "PENGANTAR FORENSIK TEKNOLOGI INFORMASI", "4TFOR-E", "SENIN", "11.10-12.50", "B214", "Michael Oktavianus S.Kom., MM."],
            ["M5032-T", "MATEMATIKA DISKRIT", "4TDIS-E", "SENIN", "13.40-15.20", "B213", "Sriwahyuningsih Piu S.SI.,MT"],
            ["K4224-T", "ANALISIS DAN DESAIN SISTEM (OBJEK-UML)", "4TADS-E", "SELASA", "07.30-09.10", "B103", "Annah S.Kom., MT."],
            ["K4224-T", "ANALISIS DAN DESAIN SISTEM (OBJEK-UML)", "4TADS-5", "KAMIS", "07.30-09.10", "B103", "Annah S.Kom., MT."],
            ["K4253-T", "ANALISIS DATA SPASIAL", "4TSDA-E", "SELASA", "09.20-11.00", "B105", "Dr. Cucut Susanto S.Kom., M.Si."],
            ["K4253-T", "ANALISIS DATA SPASIAL", "4TSDA-5", "KAMIS", "09.20-11.00", "B105", "Dr. Cucut Susanto S.Kom., M.Si."],
            ["M4223-T", "STATISTIK DAN PROBABILITAS", "4TSPB-E", "SELASA", "11.10-12.50", "B210", "Dr. Husain T ST., MT., M.Pd."],
            ["M4223-T", "STATISTIK DAN PROBABILITAS", "4TSPB-5", "KAMIS", "11.10-12.50", "B210", "Dr. Husain T ST., MT., M.Pd."],
            ["J4222-T", "ANALISIS JARINGAN KOMPUTER", "4TJKL-E", "RABU", "07.30-09.10", "B211", "Muhardi S.Kom.,MT."],
            ["U4222-T", "HUKUM TELEMATIKA", "4THTL-E", "RABU", "09.20-11.00", "B102", "Rudy Donny Liklilwatil SE.,M.Kom"],
            ["K4212-T", "PEMROGRAMAN APLIKASI MOBILE NATIVE", "4TPMN-E", "RABU", "11.10-12.50", "B209", "Khaerunnisa Hanapi S.Kom., M.Kom"]
        ],

    };

    isiJadwal.innerHTML = "";

    // BELUM PILIH KELAS
    if (kelas === "") {

        jadwalContainer.style.display = "none";
        return;
    }

    // URUTAN HARI
    const urutanHari = {
        "SENIN": 1,
        "SELASA": 2,
        "RABU": 3,
        "KAMIS": 4,
        "JUMAT": 5,
        "SABTU": 6
    };

    // SORT JADWAL
    const jadwalUrut = dataJadwal[kelas].sort((a, b) => {

        // SORT HARI
        const hariA = urutanHari[a[3]];
        const hariB = urutanHari[b[3]];

        if (hariA !== hariB) {
            return hariA - hariB;
        }

        // SORT JAM
        const jamA = a[4].split("-")[0].replace(".", ":");
        const jamB = b[4].split("-")[0].replace(".", ":");

        return jamA.localeCompare(jamB);

    });

    // TAMPILKAN
    jadwalContainer.style.display = "block";

    judulKelas.innerHTML =
        "Jadwal Kelas " + kelas;

    jadwalUrut.forEach(function(item) {

        isiJadwal.innerHTML += `

            <tr class="table-row">

                <td class="table-cell">${item[0]}</td>
                <td class="table-cell">${item[1]}</td>
                <td class="table-cell">${item[2]}</td>
                <td class="table-cell">${item[3]}</td>
                <td class="table-cell">${item[4]}</td>
                <td class="table-cell">${item[5]}</td>
                <td class="table-cell">${item[6]}</td>

            </tr>

        `;
    });
}