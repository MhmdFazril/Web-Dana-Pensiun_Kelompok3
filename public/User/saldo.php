<?php
session_start();
if( !isset($_SESSION['username']) ) {
  header("Location: ../Login/login.php");
  exit;
}

if( $_SESSION['role'] != 'Peserta') {
  header("Location: ../Login/login.php");
  exit;
}

require '../Functions/function-saldo.php';

$id = $_GET['id']; 
$data = query("SELECT data_diri.nama AS 'nama', data_diri.golongan AS 'golongan', dana.total_dana AS 'total_dana' FROM data_diri LEFT JOIN dana ON data_diri.golongan = dana.golongan WHERE data_diri.id_user = $id");
$data2 = query("SELECT dana.total_dana AS 'total_dana' FROM data_diri LEFT JOIN dana ON data_diri.golongan = dana.golongan WHERE data_diri.id_user = $id AND data_diri.status_berkas = 'approve'");
$id = $_GET['id']; 
$data_foto = query("SELECT * FROM user WHERE id_user  = $id")[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Saldo</title>

    <!-- Link tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script>
        tailwind.config = {
            theme: {
                container: {
                    center: true,
                    padding: '16px'
                },
                extend: {
                    colors: {},
                    screens: {
                        '2xl': '1320px',
                    },
                    keyframes: {}
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');
        .font-family-inter{ font-family: 'Inter', sans-serif; }
        /* .bg-sidebar { background: #0A161E; } */
        .cta-btn { color: #3d68ff; }
        .upgrade-btn { background: #1947ee; }
        .upgrade-btn:hover { background: #0038fd; }
        .active-nav-link { background: #1947ee; }
        .nav-item:hover { background: #1947ee; }
        .account-link:hover { background: #3d68ff; }
          /* *{
          border: 1px red solid;
        } */
        .modal-body {
            justify-content: left;
            justify-items: left;
        }

    </style>
</head>

<body class="bg-gray-100 font-family-inter flex">

    <aside class="relative bg-[#152A38] h-screen w-64 hidden sm:block shadow-xl">
        <div class="p-6 bg-[#0A161E]">
            <a href="index.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">User</a>
            <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> New Report
            </button>
        </div>
        <nav class="text-white text-base font-semibold pt-0">
            <a href="index.php?id=<?= $_SESSION['id_user'] ?>" class="flex items-center text-white py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="daftar.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sticky-note mr-3"></i>
                Daftar Berkas
            </a>
            <a href="krip.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
              <i class="fas fa-book-reader mr-3"></i>
              KRIP
            </a>
            <a href="saldo.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="flex items-center active-nav-link text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
              <i class="fas fa-money-bill mr-3"></i>
              Cek Saldo
            </a>
        </nav>
        <a href="../Login/logout.php"class="absolute w-full upgrade-btn bottom-0 active-nav-link text-white flex items-center justify-center py-4">
            <i class="fas fa-arrow-alt-circle-left mr-3"></i>
            Log Out
        </a>
    </aside>

    <div class="w-full flex flex-col h-screen overflow-y-hidden">
        <!-- Desktop Header -->
        <header class="w-full items-center bg-white py-2 px-6 hidden sm:flex">
            <div class="w-1/2"></div>
            <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
                <button @click="isOpen = !isOpen" class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
                  <?php if ($data_foto["foto"] > 0) : ?>
                    <img src="../../dist/images/<?= $data_foto["foto"]; ?>">   
                  <?php else : ?>
                    <img src="../../dist/images/Profile.png">
                  <?php endif ?>
                </button>
                <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
                <div x-show="isOpen" class="absolute w-32 bg-white rounded-lg shadow-lg py-2 mt-16">
                    <a href="cekakun.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="block px-4 py-2 account-link hover:text-white">Account</a>
                </div>
            </div>
        </header>

        <!-- Mobile Header & Nav -->
        <header x-data="{ isOpen: false }" class="bg-[#152A38] w-full bg-sidebar py-5 px-6 sm:hidden">
            <div class="flex items-center justify-between">
                <a href="index.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">User</a>
                <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
                    <i x-show="!isOpen" class="fas fa-bars"></i>
                    <i x-show="isOpen" class="fas fa-times"></i>
                </button>
            </div>

            <!-- Dropdown Nav -->
            <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">
                <a href="index.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="flex items-center text-white py-2 pl-4 nav-item">
                  <i class="fas fa-tachometer-alt mr-3"></i>
                  Dashboard
                </a>
                <a href="daftar.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                  <i class="fas fa-sticky-note mr-3"></i>
                  Daftar Berkas
                </a>
                <a href="krip.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                  <i class="fas fa-book-reader mr-3"></i>
                  KRIP
                </a>
                <a href="saldo.php?id=<?= $_SESSION['id_user'] ?>&role=<?= $_SESSION['role'] ?>" class="flex items-center active-nav-link text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-money-bill mr-3"></i>
                    Cek Saldo
                </a>
                <a href="../Login/logout.php" class="w-full bg-white cta-btn font-semibold py-2 mt-3 rounded-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                  <i class="fas fa-arrow-alt-circle-left mr-3"></i>
                  Log Out
                </a>
            </nav>
        </header>

        
        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <div class="flex flex-wrap">

                <div class="flex flex-wrap">
                <form class="p-20 bg-white rounded shadow-xl container">
                <img src="https://i.postimg.cc/YC973dNv/Cara-1.png" alt="cara klaim saldo">
                <br><br>

                <p class="fs-5 fw-bold">Qui facilis alias 33 omnis optio. </p>
                <p class="lh-lg">Lorem ipsum dolor sit amet. Qui quod reprehenderit At quia itaque quo harum doloremque et quibusdam autem non officia dolorem quo consequatur quibusdam aut recusandae rerum. Qui voluptate incidunt ab amet vero non consequuntur consectetur hic explicabo aliquam ut voluptas excepturi ut quos laborum. Hic officiis libero est laborum odio est ipsam nesciunt ut officia explicabo. Aut ratione incidunt qui explicabo voluptas sit vero voluptatibus id explicabo dolor est earum dolor non ducimus fugiat. Est reiciendis omnis cum quas consequatur ut nihil impedit. Id eaque cumque rem commodi atque quo doloremque provident et minus dolore ut maxime fugiat At doloremque corporis et voluptas dolor. Et nihil ipsa ut accusamus consectetur est minima eligendi vel illum consequatur At sequi accusamus. </p><br>
                <p class="fs-5 fw-bold ">Aut molestias mollitia sed velit incidunt et ratione neque. </p>
                <p class="lh-lg">Et sint inventore ea expedita maxime eum consectetur inventore aut sunt molestiae non ipsam dolore eum illum omnis ut voluptates libero. Cum molestiae autem quo unde ipsa ut incidunt fugit. Ut facilis nesciunt est totam quos aut perspiciatis harum sed nihil minima ut iste doloremque. Ut minus omnis et quasi quia At voluptatem explicabo eum consectetur ducimus. Quo officia modi et minus delectus et dignissimos saepe id eveniet totam et ipsa animi hic maiores obcaecati aut obcaecati accusamus. Sit eligendi odit ex blanditiis sapiente aut explicabo ullam aut debitis culpa et distinctio velit ut odio quae et veritatis adipisci. Est quos nulla et rerum maxime in similique omnis. </p><br>
                <p class="fs-5 fw-bold">Et libero rerum eos temporibus aliquid aut quibusdam eaque? </p>
                <p class="lh-lg">Quo ipsa autem eum deserunt rerum aut modi ipsa. Non aliquam soluta hic consectetur minus est perspiciatis esse quo dignissimos beatae! Hic voluptates quod est aspernatur repudiandae nam reiciendis galisum non rerum quia eum iusto corporis. Est harum unde non iure modi est quas consequatur ut unde sunt ex dolore molestias vel rerum dolorem sed laudantium esse. Qui galisum rerum non quod culpa qui voluptas exercitationem. 33 corporis vitae ut dolore sequi ut consequuntur eveniet vel iste quia est voluptas accusamus ab eius facere. Eos consequatur saepe At sequi galisum qui magni excepturi ex reprehenderit Quis ut nulla voluptatibus 33 voluptatum odio. </p><br>
                <p class="fs-5 fw-bold">In galisum pariatur est enim labore. </p>
                <p class="lh-lg">Qui minus nulla in optio quia ut corporis dolore. At quas enim est eveniet maiores ad voluptas quis est autem suscipit qui consequuntur tempore. In expedita officiis ut voluptatem minus sit iusto deserunt et laboriosam aspernatur est velit animi. Aut quos voluptatem est galisum neque in incidunt laboriosam et cumque similique. Sit velit totam est natus enim et laboriosam cupiditate et minus reiciendis sit aperiam dolorem qui quisquam magnam. </p>
                  </form>
                </div>        

        <form class="p-20 mt-10 bg-white rounded shadow-xl container">
            <div class="container">
                <div class="row row-cols-2 row-cols-lg-5 g-3 g-lg-3">

                    <div class="col">
                        <div class="p-7 btn btn-light bg-subtle border border-primary-subtle rounded-3 " data-bs-toggle="modal" data-bs-target="#modal1">
                            <img src="https://www.svgrepo.com/show/345389/file-document-data-health-result-archive-folder.svg" alt="Jaminan Hari Tua">
                            <br><br><i class="icon-tab icon-program-1">Jaminan Hari Tua</i>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Kriteria Pengajuan Klaim</h1>
                                    </div>
                                    <div class="modal-body bg-[#152A38] text-white">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                a. <br />
                                                b. <br />
                                                c. <br />
                                                d. <br />
                                                e. <br />
                                                f. <br />
                                                g. <br />
                                                h. <br />
                                                i. <br />
                                                j. <br />
                                                k.
                                            </div>
                                            <div class="col-sm-7">
                                                Usia Pensiun 56 Tahun <br />
                                                Usia Pensiun Perjanjian <br />
                                                Perjanjian Kerja Waktu Tertentu <br />
                                                Berhenti usaha <br />
                                                Mengundurkan diri <br />
                                                Pemutusan Hubungan Kerja <br />
                                                Meninggalkan Indonesia selamanya <br />
                                                Cacat total tetap <br />
                                                Meninggal dunia <br />
                                                Klaim (JHT) 10% <br />
                                                Klaim (JHT) 30% <br />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-7 btn btn-light border border-info-subtle rounded-3" data-bs-toggle="modal" data-bs-target="#modal2">
                            <img src="https://www.svgrepo.com/show/345379/bandage-medicine-protection-medical-healthcare-health-care.svg" alt="Jaminan Kematian">
                            <br><br><i class="icon-tab icon-program-1">Jaminan Kematian</i>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Kriteria Pengajuan Klaim Kantor Cabang</h1>
                                    </div>
                                    <div class="modal-body bg-[#152A38] text-white">
                                        <div class="row">
                                            <div>
                                                <img src="https://i.postimg.cc/zvxzz6S1/1.png" alt="Pengajuan Klaim Kantor Cabang">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-7 btn btn-light border border-warning-subtle rounded-3" data-bs-toggle="modal" data-bs-target="#modal3">
                            <img src="https://www.svgrepo.com/show/345385/consultation-consulting-laptop-doctor-healthy-medical-care.svg" alt="Jaminan Kecelakaan Kerja">
                            <br><i class="icon-tab icon-program-1">Jaminan Kecelakaan Kerja</i>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Dokumen Pengajuan Klaim</h1>
                                    </div>
                                    <div class="modal-body bg-[#152A38] text-white">
                                        <div class="row">
                                            <div>
                                                <img src="https://i.postimg.cc/yNp2NK2j/2.png" alt="Dokumen Pengajuan Klaim">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="p-7 btn btn-light border border-danger-subtle rounded-3" data-bs-toggle="modal" data-bs-target="#modal4">
                            <img src="https://www.svgrepo.com/show/345387/health-message-text-mail-medical-inbox-hospital.svg" alt="Jaminan Hari Tua">
                            <br><br><i class="icon-tab icon-program-1">Jaminan Pensiun</i>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Dokumen Pengajuan Klaim Layanan Manual</h1>
                                    </div>
                                    <div class="modal-body bg-[#152A38] text-white">
                                        <div class="row">
                                            <div>
                                                <img src="https://i.postimg.cc/6qXcW16z/3.png" alt="Pengajuan Klaim Layanan Manual">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col" data-bs-toggle="modal" data-bs-target="#modal5">
                        <div class="p-7 btn btn-light border border-success-subtle rounded-3">
                            <img src="https://www.svgrepo.com/show/345400/mobile-phone-chat-health-device-telephone-smartphone.svg" alt="Jaminan Kehilangan Pekerjaan">
                            <br><i class="icon-tab icon-program-1">Jaminan Kehilangan Pekerjaan</i>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="modal5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header ">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Dokumen Pengajuan Klaim (JKP)</h1>
                                    </div>
                                    <div class="modal-body bg-[#152A38] text-white">
                                        <div class="row">
                                            <div>
                                                <img src="https://i.postimg.cc/KvYLPyWB/4.png" alt="Pengajuan Klaim (JKP)">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer ">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
                    <form class="p-8 mt-5 rounded shadow-xl container bg-[#152A38]">

                        <div class="container mt-3 text-white">
                            <?php foreach ($data as $data) : ?>
                                <div class="row">
                                    <div class="col-sm-2">
                                        Nama <br />
                                        Golongan <br />
                                        Dana Pensiun <br /><br>
                                    </div>
                                    <div class="col-sm-5">
                                        : <?= $data["nama"]; ?> <br />
                                        : <?= $data["golongan"]; ?> <br />
                                        <?php if ($data2 > [0]) : ?>
                                        <?php foreach ($data2 as $data2) : ?>
                                            : Rp. <?= number_format($data2["total_dana"]); ?>
                                        <?php endforeach; ?>
                                        <?php else : ?>
                                            : Rp. -
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </form>
                </div>
        </div><br>

        

        
        </main><br>

        <footer class="w-full bg-white text-center p-4">
            Copyright to <a target="_blank" href="https://github.com/Queniex/Aplikasi-Pensiun" class="underline text-[#152A38] hover:text-blue-500">Kelompok 3</a><br>
            All Right Reserved
        </footer>
    </div>

    </div>

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
</body>

</html>