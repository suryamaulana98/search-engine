<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Search Engine</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .hero {
            background: linear-gradient(135deg, #990d0d, #25ebb3);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .search-box {
            max-width: 700px;
            margin: auto;
        }

        .result-card {
            border-radius: 15px;
            border: none;
            transition: 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(165, 27, 27, 0.1);
        }

        .book-img {
            width: 90px;
        }

        .footer {
            margin-top: 60px;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">Search Engine Buku</span>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <h1 class="fw-bold mb-3">Cari Buku Favoritmu</h1>
        <p class="mb-4">Search engine sederhana berbasis TF-IDF</p>

        <div class="search-box">
            <form onsubmit="return false">
                <div class="input-group input-group-lg shadow">
                    <input type="text" class="form-control" placeholder="Masukkan kata kunci..." id="cari">
                    <select class="form-select" id="rank" style="max-width:120px;">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                    </select>
                    <button class="btn btn-light fw-bold" id="search">Search</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Result -->
<div class="container mt-5">
    <div class="row g-4" id="content"></div>
</div>

<div class="footer">
    © {{ date('Y') }} - By Surya Maulana Akhmad
</div>

<script>
    $('#search').click(function () {

        let query = $('#cari').val();
        let rank = $('#rank').val();

        if(query === ''){
            alert("Masukkan kata kunci terlebih dahulu");
            return;
        }

        $('#content').html('<div class="text-center">Loading...</div>');

        $.ajax({
            url: "{{ route('search') }}",
            method: "GET",
            data: {
                q: query,
                rank: rank
            },
            success: function (response) {
                $('#content').html('');
                let data = JSON.parse(response);

                if(data.length === 0){
                    $('#content').html('<div class="text-center"><h5>Data tidak ditemukan</h5></div>');
                }

                data.forEach(function(item){
                    $('#content').append(item);
                });
            }
        });
    });
</script>

</body>
</html>