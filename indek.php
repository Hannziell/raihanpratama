<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "omega";
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil data jadwal dari database
$jadwal_query = "SELECT j.*, p.nama_program 
                FROM jadwal j 
                JOIN program p ON j.program_id = p.id 
                WHERE j.status = 'aktif' AND j.tanggal_mulai >= CURDATE() 
                ORDER BY j.tanggal_mulai ASC";
$jadwal_result = mysqli_query($conn, $jadwal_query);
$jadwal_list = [];
while ($row = mysqli_fetch_assoc($jadwal_result)) {
    $jadwal_list[] = $row;
}

// Ambil data program dari database
$program_query = "SELECT * FROM program WHERE status = 'aktif' ORDER BY nama_program ASC";
$program_result = mysqli_query($conn, $program_query);
$program_list = [];
while ($row = mysqli_fetch_assoc($program_result)) {
    $program_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PT Omega Safety Indonesia</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- AOS Animate on Scroll -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* ============================================
     PT Omega Safety Indonesia - Enhanced Stylesheet
     ============================================ */

    /* === ROOT VARIABLES === */
    :root {
      --main-blue: #1B4B7A;
      --accent-teal: #2DD4BF;
      --dark-teal: #0D9488;
      --light-teal: #7DD3FC;
      --light-blue: #E6F2FF;
      --transition-smooth: all 0.3s ease;
      --shadow-light: 0 5px 15px rgba(0,0,0,0.08);
      --shadow-medium: 0 10px 25px rgba(0,0,0,0.15);
      --shadow-heavy: 0 15px 35px rgba(0,0,0,0.2);
    }

    body {
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
    }

    /* === NAVBAR === */
    .navbar-custom {
      background-color: rgba(255, 255, 255, 0.98);
      transition: background-color 0.3s ease-in-out, padding 0.3s;
      box-shadow: 0 2px 15px rgba(27, 75, 122, 0.1);
      border-bottom: 2px solid var(--accent-teal);
      padding: 15px 0;
    }

    .navbar-custom.scrolled {
      background-color: #ffffff !important;
      padding: 8px 0;
      box-shadow: 0 5px 20px rgba(27, 75, 122, 0.15);
    }

    .navbar-custom .nav-link {
      color: var(--main-blue) !important;
      font-weight: 500;
      margin: 0 8px;
      position: relative;
      transition: var(--transition-smooth);
    }

    .navbar-custom .nav-link:hover {
      color: var(--accent-teal) !important;
    }

    .navbar-custom .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--accent-teal), var(--dark-teal));
      transition: width 0.3s ease;
    }

    .navbar-custom .nav-link:hover::after {
      width: 100%;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.3rem;
    }

    .navbar-brand img {
      height: 45px;
      width: auto;
      transition: transform 0.3s ease;
    }

    .navbar-brand img:hover {
      transform: scale(1.05);
    }

    .navbar-toggler {
      border: none;
      padding: 5px;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2827, 75, 122, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

        /* Responsive adjustments for navbar brand */
    @media (max-width: 992px) {
        .navbar-brand .d-flex.flex-column span {
            display: none;
        }
        
        .navbar-brand img {
            margin-right: 0 !important;
        }
    }

    /* Optional: Show company name on medium screens */
    @media (min-width: 768px) and (max-width: 992px) {
        .navbar-brand .d-flex.flex-column span {
            display: block;
            font-size: 0.9rem !important;
        }
        
        .navbar-brand .d-flex.flex-column span:last-child {
            font-size: 0.7rem !important;
        }
    }

    /* === HERO SECTION === */
    .hero-clean {
      height: 90vh;
      background: 
        linear-gradient(135deg, rgba(27, 75, 122, 0.85) 0%, rgba(13, 148, 136, 0.8) 100%),
        url('data:image/webp;base64,UklGRmA4AABXRUJQVlA4IFQ4AADQAAGdASrXARcBPp1InUulpCKnpZR7GPATiWdtxPJZj/a/j4lFRuf+z3qwDawCHFJHTQLb3AholSa+ZcZiFd+lN9PzpPRgdVt6AHTKY4BLB8/67/0XsVL2/5Hgh/f/5VnT7LfmtqC4Q9vtq3mX+8GifiF/WdG/fh/zPUJ8pHwq/tv/dISOj/DgesYXkxgHamn7Kyd7r57KCYB2ppz7pQ4NvrJMAKH0FHYymoBecd0gDqQOS/zVYHLI22S2t8rvSo6KzQYYfWYrqaLJ6QwsftUQxBIOaTedszXzxUDUqM+DWxdRVipDwh4MIg7fQAbttWXgHGxzITAHDDQVcPrs7CgKdKjBwA9Vmndy4t4ln6667eL6qbx1e0OQxX2Vv4Uda74ksgEv5jungiEktAqdD6dLenNu70s1mt3uBBBdU0mb9Nj+n1NBT0yZYUPB5AAK1PibC0PKl31jSifW6r7qm929rYGSonOhSdfeuSPQcAbKuxDzYl3LptUlCShrE/6+niN33zFejerD02UcreUMJYVgkO0PuH65GrdDoeww8tg1K3i1sfNi1BOHzO35TRyNIQSmqsSyl7GLlPJ/qT2sXOCQu/xjp0SgQuuat+iE5lYNncDdGEozadKVQgY2EeSvrHkDyBZgZ8MveROQllPq3gndNZb4Qrc9YZy7zbFsKmk38O8y+tLk7zgRFcG+cuk4ez5IltaZ2xQsWeWygFnTDXWRhgVcL5F7eTe8+YGRmvyO5+q8iyuHDDyJcX3ukbJH84CodOvhID3TI4A93rB1s0X6t/zMXm63AHtPrzn/RQnPvHzmarjuq4GelEPmWfA2BMOAP4fjvrfSs4SKT9nNSdvksPWk/uaEn+ObHVBBcKPWiLXe29wpPAvIHIjvDgh1UZq1gHz0mhnR+ddH/4RTIinuirh/FR0OF7swZTgoDTdwRb0a2TZkILFFwlq8ioFDgsRGN99EYDGzEbVXXs0Ar9KQiLVnkPt7yxpMbqPIRw1WUQigvIilU7uB1HnatRaha9hmGgs+E8LdEo+pxFUZHedHL8nKZdgx+Xv0m86zJD5/mftf4cWqv+6zE1BStyRgsDcFHXzZDUaq3uh3QEv1YImlIfZ8eS1X3puWhBXU6s/vrshWQ8/GInrDto74Hl5s2Ucco+FgNJUMJSbIm2pl7LF2eojSonAxe8zIukyT3G4+5jyvKeeOtiw2R1QQD7Z95O3jG67IAPQQ3hPGHu7Q+rJEi0m6xwEevjZWG6sDDUnkErHZMr5J2Y0JgnqNkn9kb5HLKv/6la43hhQ1KV8QF+hkceFqd+lJez9PufBtqvz/Mf50gekLRj5gnegRvhLXuODBN2LrV9VS65QUah2TinDDsq8P+LAbavU+ICUmCIKLEduKEsPT3arum/JawL4bL9M5qHCTLXh/bwmW6IdCWitZ05QBsi/be+nKwWJXpmLmsTN6Oh0ZEd1eUnUo+gl47ZNklSeARW17ufBr2W1Kuuz7Ee4Efxh1w56tLrX/xfxVhShWpcO9JA8wha3/Hf58thibFZ7pWW/Cd9jc7dm4Tc01l7ct7e4JJNSHZIdGqzfCCZVQklNrhtszfTvQVKrkQ+YnrGohFe0vlMpsHdnu2m9slxh3qd0j7F+Ps56CpOWkw48CWXFcccs5jXb/v6dztORpqSfHKB0Pk+xA3/QRYRJRHtONL3s+3nIw9ymQe1sovTvSZiaxcPj/yeYMK7HxIa9G88aFi3xOAN462ojOkw0pf7qLdwceGFgI4C7H59vEmTtyp3yxIhU/jlJi2J2Pk2G76e3uyHP/n4QP81N1u1711b+X6yvyMd/v+SvqmpidfiSC8dzEcLgZ9csT26M+w/5yto/tNoq7rKl/rcbv3nv53VJWA1bOIQvUejZTgkMv/4WkY/704nnxvmS0Q9RKkls3nduSiVH66ndmkS/tIA0kK27Hn9Vo/5Yu3zh79MR8do8/h22G3XYq7uUe712bTksTIAWbHQHCawMUT+bDy+eBri/+2r80upmk4pnNvdcdcey8mhwFA/HiFYwAGyxIUtQru1bChmqIJChHzr2lCWrhHRZe9JT5l9UwIINFsTT1ldY7S+HPa0vNgs9Y8UKmi7sa/H/ewqaAXJdIAYBer641fJ6EK4A6eBQLtwz9OQuvesufG2UxpDfwfhqOPVVCCzVAhbZilg/4lTnQtMgeIm2b+TFAQZt9OGpA1kzNjhuxfIcOPj/JJ8rK6UO5MQ0vGcd34w5AhZJDiSBMjcurnsdgh6c4WWyt/eR/ZzuSD9QbkgczJqUt8X4qelgDjKeYIQZZs93rDl9i2lw4VYx1gWQSPyZG7fs//70PohRvTX1Ywlmg2JOHxpVapTktdrbYIf2ZcJ6VnbvRX8csJj/vlWiLhtF0k1ccOPLFbcp3C77ENXMqoPE6VxbKYqTcKdulSsffrFD/3fTTmbAXtFD24NV0A0o4178jvrAkfJ62PdG+Nhxq65CbWRiuN38L6TZm175949gJhzl/Vmf2cRdSt9LWiI1Wy+g0KV/sVgXeV6oXVxlg4Othmmg7oOMv0azPy67p2d+hJ6uwyjqi0L9DVtdEnTZ5VeGRULkPq5IxwX/feVjvwUkDY8a6k9LSMxPu0lTlxM1G1Q+yzNMnZjBPMlNZ4K9Ml5+Xsj2Oay1xdybWtaLaYMp/Q6iT/rVHouYskA7NknG39cdpki4kHFvXN6Mcbs6ViUAziBVB5VtLJYtqGGmpMWzh4aXrIQtUfKRBIAD++nFMp4IGMzu8KxxaAgYkLqJDda3JBdDI1202mkfeKPcT7/faWpntG9hp+1RSZj8VZfEB0W5orpkwThWv7r5fBvcv2RAzF3a12aK2tyDvCRKtSCw1m1YQWRtrlQCABaYOemziHfXn45rLT4jxC7aT4J/WvT/VXvbYV6gpJQZVx44RGTag5peCVT1vrJ1W12VAqX9FaRvx3yjdKPqhf6dlab4nCrni078JHTa27U6Qk/DcfmOw0Gd31FKdePoWgwX8P33rmoKFohfYEnuLVAikqi6v1fvs5DeO40q4kNl0bMR8gzxlPqzjh2CmwZT/GPDMCFJzYJjXFspG2E1v849+yjR4eRaYmnQyKkuTl9AmY2RF4pg/bQ6B6PpPIEUVEZLryMZXfbBXOPitIP/vk/q5hAQ47cbO4Vgk9tiU2q2Mrsksg/cjsT38Q1kev5+3CraJwm/6slcMfYCtMVuRjqX4k7BJkzZKIHbvmOmnt2gnOTfdU+z15ayBkHTLt+rgORPhWm6q+tVsc1mNslOXrGwt+CuO7bs5nHeitz99BUQpcU+1RO9ECllrZP4PJ0dQjwdNBwzJxKHNZxjMLAF/tejUZDnluc0XS8j8P7wKBqx+DeT3s9XnpB0MtdjxaXo+VrJz3tH3A5fU+jnh5z/0QCACwcKdn1NqY6Zw3ZxAk3S9PgsY8P9YTwxOlN7L6BKUXr3oJJtUn+go1NfTRRNVx9TZH6L75kTHfsmkpTvFpI4I+TEaGjMZ0r6JPx6LzaD1sLH6wxreDYQSyk5BDBq2X+ixVfqvzTBuFMwDAxC22WJVtCExBBQFrwKp163mKxi31uWU2l7aDBTWNgARLYFOaonmQI0KDM7HegW1N1ebr3a/Oyz79MH2VLpCEdyhT5ouEEfdGFvY/6sGMuHLoi1ZKx7zB02p2NtKDaXZ+wlTTNZQgyawYDWa5cvnfvarep/3to99hwRizu7AmmEPB2P6tn813KsAJNa4cvtqVAUni8lZKMNKQJCO/jSAz9duP4jOYO/+MIiwZL4nMJ7AgDp4sMoXL/5M5jm3HHTumymbXDrD0bGiDdmbQDYgNX7IfTYsS9tk4tOS5kx3WWNLTEa5TwLsoCiEXu/XtZBeaYomIKH6qobQ5JFqm1GaVvoJInT/Ow31cFLCp1cS3SALG2oG6Gvt1m1tuz+eZbzpSWjjR8BgCP7Xr2AH5wMiwhtZRUzAYe07TYcmhAU+M8mAbFYVJulgyyv0mpaURSxCAFzhn/+QIHRzr/DgoKm0STHkM4gmwvB9I19/ANEZNNpWDsw0wHfyJ3FB9CxzGXnB6vbWRKMWkzAhp/jaFuDx+WerNwbGgfO9HXRybkil3xAZ78+90BPaBUtHkY44jvo5NZ3GTd9EK851sNhXOs4u6Ylyk1R5LUH8nEf0T+6pyNoLrNRhtPCLEbYpHJuGb+dUkmHfN3Xf/Jc0reMy+3MAKmPedkNeb9mihMk0kDQ8yOtsgdHtsn8krG5V9aM2Bpqb4GGc+GrJ24C7RU+IH3Jt/gzpsJ2/kpXfP1I6QDL0f0U+5JC44EYIsYjgImy5ZkRNS2uK2VMZmN2F3Xt/MEeOGA6xrpW5pn8sxONBrjw4A0wbRcJf8OdcKBkVhFA76Q+Pz2fexQgZV689A/g8wigFI9Gdfx0ccLbY9w1ATTkTK2rZfmOJjyFCBVQpAk4SSbjF0M+9v6M5wWBTpwMqXvIIgGm7283pVtW/r7Ga5jzSHDZO/PqQckmjlF2IsL4oGsg1gn1LN6ZOBdj9RrxJKxUtoeUMeamkjDH5Wa8B2PYkFS5EV1SdAFzZoCUpHSbczUx6evUoOT18rqNcRyaQNzi0RJRhCb2wg0vDi2NqZWB95qFX926glaJUvFESJZClhDkkt2XfC5utVYAM8/Xond4n+g9W99IYbjj2f5xwRPPR8mBHypL8PajtGDANlgcmtW5f1XqCfvX4w0HT0FDD8CzxpLFDtLOsNzquLS3K77nAGsjhVXic8PTKuc4l1DeGn4FVBA5WWnEIEX2E8yo3oBUQgF3ijXktJvWwbnveciO+C1ieyyuNd3gAmLWydQmqPU82Og08rZ/DHGrGin3G8+hqBhw+16hkmjMRCjX1j/LoWvLhSltqfuG+frzEZyVQZpoK2S+ulbjfheSW/6OgShYrOjEONa5m3NTF56pCgM8KI/B0zcaYuVQTch1QCAx1CkQ4sQXeTpYOAg6f73+89jD6IrRXIthL6wxuQo6H5CJGUjU26sS1O6YEvuUSotQcvqa1RkxWXwlMmvwvVR++xbf9sXFR7XMjuw8l9gFTCZdEI9/1fSnNwxBA1kM8shx01hS9KCvLwqPI0uHSE8E5c4XIpE6Tee17Bwsc0hG3lGxW9yMBxwW8gVIxONrudG7gU/8y2Pgc6Jf96zQXPvtJOojjKnWNfI8ia1PTzchedeVWdsO1zaDhjLlXSMojUrBJSePj07xcwEYigfLfHGq7HeB67g+kvhMHyf+Jgx8+4GlmTUp6AczjAuxBWaQFsIhxDzusIB/FlVKLp7Sagi7rij+ZJirPe0y/fox/l7Pzmw32InQSGhzURIjVNWVHz7iIjVIED43Aq4TDGr3q6430f65nY1xtJNVZXPWoJon5tYTcyRXVn1+z/gWt40dMJTR+TNWDfG5SH6tlrwIkS4Nc0jelWmRZeodq+cyc47uO6G80UHdc3xYx7//uUCfKaZLIjo2d0rd/wRSUn2rpebRlew2ja9t9OutFFae0j9Ohv1rZr6gq930tBLeIwhSzebNbqUaVz68E3w+zEKva0dOl+oM37MYWGhkjIbnKe9wl3J1xxxsAW9TMgqsrFzuRRHX7h1XKPw6OW++i0n0ym7w9ZrhBqOvEHenQGAKouXvDOYS0dg6b9PrWqORfUzCQlBfZRIC7+EU2wXtFeI/Mer4qsDo0SgaS8W/ZEYElD8eT39F1XAkD5ESHyJP2HYDaoOQ3j5TqkHtS/XEoEvgXnPl0pikWWd3ChU79mZ3GDkvWvNQHLnmqMcGAtFjBzTR2pgBIn7Ug6nkQ8HqZUHg0DptCzxGaLu6VCyUyGD3fKGL9qXkfu4YDa07ptz+BS4oZBJgGJ+M2WunopphL5BWl2rSN7LbrinFC8RsFxGEGncxgkvzSOk91/fa3hpjp0Iq9g4YsysZVyu6b6mnMwhsh+NyTwaIqV143yxR2Rp0fnJboEgGuKumQjGnf9M3Mfb3oQeWXV/NOlVLrk0H/h9+3fI0ufrJ1mLxaHizP1fN5R47zzeRZ8KGqcercn5fKmfsma8D9CUuHJSsjE7xNuXKv09eagqv6fdcgjvRcVLrlrnn0dgRwMVnMvHPjkD45Q9+ao7mDjUurYRKlNvLWecv9kvXs/0gtAQI28OAjtL0smB3Su0H3ua/SDf3ff9nO10W3AOIWHrSK0/ocGjvxLJ0yJsECl4v3XUmL9+nYhuu/9HBDk9YEoTWzozjcZDI78xBXdWVLcz0lJoKnbHn1LtXsHwT6u2keAMVn9XS0wQvagJIZoD6FpSioelxR2quSlQarEN4tB1kPNNw+gqGYMpD86eaoH6ncxwHWsqBWJenFQuu67zwr4mcyxE3BClUfQJ9//yYrZPvvewVH48LsSMwUz9htTC1LG9bPTx0DnQ9DUoBTg1TMWaO8kIollrhmNALvgZnjuQHaQNfJo2gOuWsdlsSnvDgAxNQ0E7UEwDgZvv0f8NFkQZqIS4r+UqigetX5SSEg9iuhkVoF3I48+cCuFxlGkDuvl2dsGq6WU+keu5ZMOUjh7Yl6k3A2MmJubF9HkBBhnJ4zaDSvrfBKK837I6N60YyfqiFz95v8fdf6RqRa+vRNJiy3sFGalc3JK6dmmze1TnKWSpvOTz7/d0y3utad1SCfpNb10BeqAt8TGtKZXTfzDiBWrty/ZQXM4OugCgivO/i2mmPR49PVVji/VuDSlBse18vcZbPlbVNnAA+ijFstNRGUNNxCgtJPvH1R6JxLWzWMwarLUlgngpNbheBHomJJapYvzTngqIt1WF53DOeHmtjdzsP3aUAcI22b8t4EhWLSFeTJmq2PW6//4JSKkiVs6GtgwOuJ3xdWUNiwwxdgIytISUTPmmx2a/ChUMU4xOe+Ojf+hBXY8V6/BpDsLJ2WbhAEsWk05y8mgwUh/fZx0uXjoBWdg8av8Yt2I6B2fBqduU2OkABMGthLVEuzmVcTsr0pzKSj6NEfy/f6j3KmkAlDZB/xbOnYOgYgJIHzmYKDhTb7jc8PPu4sM2AnietZqMeD39BLwp8DhJB8/c2FdATUpXOBwwKzfLULP1r7f7PXfyx+f6pugQAh7OhkAwnl2VvczLfx0FPAK6C0qpaKuWsPbUC78+9y1yUXwE6L90dnOOyCxTkeeVwmolpXx3IG16yyoJvpzL1QNWP1QNiuER4Z3/jBCaOGZhJ+Lqtj4aT3myt5LaCkL+965pMbT1t7ehVzjGQlk7ZMulIRGHguyyv2LJg3VfQjadCqScbEeXINg1LPY1JzbGN6RNPUvTA2oEZdEhqw7V+Lob0kC9VIiMsad3uPKVLhd3XmC7yW3DxRGlDya7GAFEJJUG/nrQrLYbMQQPdReaEBZdowoMkq9h43Xrb+mD5bmgppjxz4MM37v4sBCHJV7Q5W0vXYwHZrjs8iwsut8ncph4l94Ag9v/aKYLGOARgjJEBJsgkggl0cz9sUQqUxgjjUstsRCG0daF8jEyCTwKwRFJ6FBk/oNKp7CNRx/F3uDLB67MqN9F6HKIOzqkLB7NO5+Mc7x7fkBYVoGFRutikgkmTE8FR++DpSooI+nOaTHRcfbMUegiuE1iQ9hehoIR+sJolieYQNaC0gjnTUTdUtSQ1O1nukcDHIqe3JjX78ZUx0DwX1Q4cphmmACkw6tPDK4+MV35BOZOW40M+5lEnvguvWVUXj/SXtkH21j7WeJiMEzhJbAjx8BZwW130Q+Cd4ATsUy4bJLdK6nP16KXmRyFhHXrrIaSoBa/N4qU0Vjk0Ma3jnLS7c+rB+A+BV/PyzlhHp5wOuCEyCLWjDxYU2vVcuU4Zsz9AAGtXYXiEJuY2nCQGjMh+gwPtFCxbuJVJ6Xd/TdqAug+TsX2n9t9/unFoZdbRIZQkKk5TSIBajcK7leWNV+UxOCEt/6+GwQWehtrk/ppISYUmyRMi88rWX20HlpxEcQb3H+CJhxtZS15uP6h4iNuNO1T7paTmBp3uEnD8tpxC2YZ+JeW8nVooE7/+uRz9EvU5iMHTYozPJk0cHPv7qZ5VSzj8S8/ySvPwiBDEXXA0vAd/mC6gtaDSuyQl6rTN6FU/1dKE4hJrR/aRaFLH/ZbwJpoUU9QK3cddN1ns8PsJk293XG52GePmwTPA6YI4crCrqj0mh2R/xn7WzX4VQYS/RopApwk4Bj+69L30NojvKYAjC60TSCflp/vA5B3gTT6NpZrzp8JnT4xQqC4XvZAQG/NcQ65bQ+y0TS6zGC8R8FCcPwHZ0YqX+JLwG3IbcvnQEizcB/PPwE7DbACTFLawgi4gkE3IjFWOaB5JZ2dY6paIN4eokBiAT8CUmOaivwL/OXzeRXEcRDq9woRVEsdRjuKjb/oYBUhafWVd9dI2l+wVSv5R3bqqDlLO/xUw+x3fQpKXJy0+9nZwwR31x0cau33JmieoJBudYLBoGkvHYJG/Qo7o2839YrH+IzHZyRnGUJOf1/oCywKBQjtZgBrArAawlMZ4IW8GUwdQ44hUyyRHtAecjVaNZ68+KZUqC08E9wKNQ31C1S2GAde8nIplfSmM1zrjux/utN86vR5vfLZTEON+yj0ia2v838Hkp3rqdP4VGZbizzfRVtJHPZZ62Wod0he67mSnVvgNvid/yAc440fJ976fP77ghsUaKD4qEUSBvQkz0eSrMu5c85IDjJoJ6+8deweXh8ah4YQKHmSN7yU8Dg9c2OpjK5tBRLOwDUdvezvocprdazyuhBnBePb52QB+GiLQckZoPzLLu3Qy1K3a5XwkNrmfXF1Z/kUcDISGx50xdMJo3skX3qD4kaFLg8SQZznjswyhPZICF8tyPQYVMjtHX29aVbghbs/2vy9JeDfKkjnzxzHMKzIejQU03aGA0Str2jHOFOGv09oaewSmJkZoFiWqbeyI+Knjx0DuK5rLlY3JkTwQkKyY0igoRjlFje/LtMQNg+G+jrXmEL9cJF0D/3NCUXQB1askJUZYGWP56Le3rvtAMXhmxMCSE41qa2RdbNEE2bl9KgfsLj5lPeOucmXaqHPsj2/pGKza8VD4nrLJldFT67UyXzFzQz8ZXy0xZwUMx3xWy8o5rHZDOfoSDnq77RqAm0eayt7PpfukQFY7ET7WbcD6K4gVfDD8I52j4rXx18WHqPOlGc386nPqy/aumu2J859BlMVq0q1RPjCAXVJxxvpuXkUOA6ZVgY6GG2/NwpPANSm8x/beTsAfY+cXEIcpb7I2acbIyqDcQZ51u8A6gyeVVQgaN5Yom6t6WGEtd6pADtaHFvLcMrfmaDwQFNdsEs7vpBWgsD/iceqSvoegiqhqSB6e4lGbgwFXF+dMJ96p9qfsvkXsp58ejpJ5OoUmEolomdY/PB6wBH6NE9oyha8dvLYR6ctWjCoo6hKi5b2/KWk7qGazD2t1MjGJhqp+U0MzgCI1DTAbUiBipV+buFqZlpJKTFKmp7TYx0rK50zALdJ4PettCRMKA7sDPhGr/H1aJwE/QeuZeVoXYGJ/Cc92GFm0ciBQtGJ20cE5LRv9wThr3NkGqyPfabnxoHAOfJU/nObqcQQlZFeol4dSDkvvJVZqY5jSdiHF3AFVKwqGLpeQ/Q4gmSnnvRHfaYtgDB9/D2etJkMkA3vL6QcQpMfSBiuc3LwLDXjDhpxt789cO/ClvvtwxhK69lNQSUrdMSrz9nTjOqAsdE88YtnDaIIyXny6iThNUIbux6/9egA73JNlCTq7wOAz4oPQm7tXZB5bQZa1u8yObdbgu2dr2v6hhZh5En/9iWZ1Ku7nV498CufreWOfl2AQg2ISYuZ+L1HI3cOTKE9GduOIL91a/R4vmZeiujyasUzH5FUv2wXgkwpM/k36aeLDVyS6ENrrzbTjMR8QSOy1Dxvmwu21FsL0Ioa53oEqx18KxaXQ7j6m8ze2FN6FLOJVM/qNCfKJBYUKz07GVqJa1lolG8cYkLWxyoaLQjkOYzPkN1MRb5MLcMUPvHFaL6EcQGtTnVkb5fUm7q2lzAWV7qdtRMwjwnK4L34onqrKmoTfV2XB1z3XyLMmsOzyBEeDTARIvbAYDF/10zQmI6BH9V5oHHeemGgAy3vGLlX642CLj/J9OzJM0gRuE8z2rMkzk5rbV/wWtDx06SDccwDl4gDThDj+txkf5yBsUfVr5D11lASQIg5RccH84T6xqJuW72/A5wzHE6Hi3GvMhvRfj90fyB8ucxe4JMYvRvZnUiukgq1RSXEcMSZ+BKP4PA81fga8NItcXcZtLdiAjufuJpxuqz5h2PTQpLEtpt0fAG7aIyapXmrMaPD6wa8du/ygC9ZL/kW1jKrjlSaF6+BMhchi04vUslspudrKg6s8RQp7OUu95xkEdqnMGoB6CRMmcwtaR6FkCoXsJRBX0KmF01jcn+yAwWK7jmta3JAXh+RSW1fkUO4YTPminmWIj3OKZg/HgDfvcRW+s2FR/migHMreaDq9WuXIkk7HaAMyzF+XuNByomqjhLMyvi9/osgVqFEC7ttWfS+qFyZHib7jCvnF+Ha9Pvwk26xp+DQkr3E6Ba7JVbAqtIcadXkjSsea5xC0V3dRT6Ou+i9V2r7JOwAyMSSlb3PCee8YxcFLhuvo/k+FkLjzYGb53svQi4Z1y1LrlNvxuPTUPXHhhV21b4gjY79dRvfbkT1RsTJyMZlcCIdKhYgvc2IF9d9GL3eoegCuKDYiAqJc91TcdqbK4iTEGFg+rlMZIZkrm/q5RnW8PFA+nIYvzAMBAWQmR14It8IDJtEUWVlPF6euL0FGFiT99s5mUr9Ia1Fcqz5HtJRSSbT8A2CCzx+zuHy5tuAAmW9eACCdXXqb4PR9u2A2lN1JVuNGYubjaDfU4IWNmOwpraCNwwS8L+mn0yBdgDJu42ku4ZD54A3n3LvvY8jq52wLTapc6UOCQTKkK821TNl2ZibjyiN3yunKH3vYX6mG02/RD0yGgRnJzD0aA7biPZ9olGFyw/qYbZIFN0oPspSpPbGmW0aNXQx6e5gB3qN1ySw2EklYY9dwdq7USYSpQpd3L5qbKiRWopo6jKnyId9VLVHrIVs1wXdPHVu5q90fWMM+TU+DKRAg+LSc9gFT1ynDjvt24ZS+0JX2SzmSEGcTxls6JoJw5U+y/s+UGGEhRxZSNJlFeN/7hEY5OWjaYEhTwd/I0TJw3WzuinlXPjCySEN4xjV5IncMgCURY/y3Fz3WrghxutR6hAkcA1yG625yT6rRJDIMDmbdRqmiwf7K0eaBKiej+bPmm3kd7pPNuq2VpXtefpkDgV9R5YHCaLuwd2L05EMuvD9lArOHYzqYlFFLf8wZgGG0bf+ZTg0ddmVNtoEHCk6ydTQBeoQ5SYZjD8FXFg/CoADu/VB2pYU7axog6T0oknql7ZFQlEq9xyy7he6NnEXtoa7ZAp6rlDgQt1YfJ5ORn4h43VmYLFYJbrsTmHToZb6m86wJcrqHhAVPdSkaOS8l3oxcyvC34Hnx0F/9V/FHe35DWcajdODHBB97EL9i0JEXorpOr2diSXZYzWx4ZEWnN3dMefbqrAEV1xWzNWyXiwUPCqXWLqDG7Ek6To/tIBna5K7c/oSKJfA/2QRFBkJAZjsqEtI3GJyEPJb1JhrXRqbqD0btHrD+Cd+++zyhLit5zEl4PHdaqvYhfdr2/kwqL7g+FL4qrZKm6POQ2ZVk+4EZoZ4qhUlXc+zg0PMK0YGwSA7/do4TE5EzBVDFu1IjLZ5Db9ki9aRWDH5CMaa9xaViKCN6rguXKni9c0c2LL1FmnLjyAP7fM956WlwayhVFB23j/wNa5PWRaf2WmKOuzONeXfkof+eMNtKFfyQlrwq5YTuoeNx2O+8cfViugCW6r8qClAe+VJi7s76JwT2k4jrXmulLz+buGwDCKuJuIOtvDFsDbYmIaAJEBMfUt3c1tarMhYXR2l8GmnYbjGvICXHU1D19PFC1Xj2oqBFHoS7zJ/mZFksKFlZXIc7QNvw46xgY5XWgiFfooOCaL/Uy0Ue1F0JxgVU28XPAdHjCNg3/Y2MDv8sCTNI0Mk0nDAS8UWkgxPqyfdDmvoFZvtDHfR1sb05l7zMAkW2MTQpSox11yKBct1z/gg0tKZ1vIPbvWJg9BgpqXVKh9lNSROe8cC/lY0sgmCkkmmKdL6zXYZhWFF8Tk8W+u7mofiuVFHYwD5lj81YxoqRhvvXUz/u5dIiPHko3hChitGPjdxuioccCei1k5f+1bgsVg7LJpsCBftW6j3pvj2ncGgXNdQiYz5x72Mjr5E60bpW++wkbZPLuytd02reAvOXJbFUJ3LG69zX7KdGT1aDWQ9YwilC/24pkcKfepttUQjXgFm9RLblEhqx9UhJRFKqEvI6PTwphmI0awml/aPpfswQllQtyW6VIPVP080mlB7BNbcg9QgcFRF6pFtdKB5TakK+4IS+onTEAzYInJCzsPJOhfxtomlrXZCfLvXVvk6yD/z8sfCnSHRgRZMXQPFt3NDSOc8iNfM/99p4yFn/RwMCTSTMjmhE6oAk4FkE1iXB1iNc4f8A7iy5DjItOMl+5v/CVHHEY9jEL+wFrsUd40XFNwZfOUDCOUF8f33G5mcoG+BCPwWeOF3aFecZ4nqAEwglWH8SGtbHozjdXjGBV64XnULdLigWPwZcWbHLYKwzoEObAGgOzwXubXaJH81uta+vFyEXvLfTY6HPQMP+p1IOQDnpQuhnqHvwrjcdx3J7oZ3CgJ6jX5ZeWcQKEzvCr/gkMId81Sdken/NGauRS3G3Fe/dfa8sRIr1u/sx9VXt2yfC91HdFk4hGcfAaLzZj6Mz9RfVIKxmU9FwPpby/Nx2t71hHR853dZlAp2/9nuI+w4iArAC/wMayReV8YjBdlZUiVP03Ypo6eewD+xQkyURJOcvpytrxwK4t4STYR87MwyBQQ6xdPKzuKMg6IkTXijJk0Ym8l/+ZsBevCAmq528SwM/jHmlhAEQuORugNfMWFPr/+eKJoJdt+5KzSFo3pY8PObXJhVUvXFbnIYxJTSHSMFlAemsGj2lLScoX+MJrq8H/myCM8DCSplvzKi8IKlI49wL5+BM16S4snENJLvUTu/AdYLNtv8++Em/xzF957HjakYbLzYW00asKyAgIycrkhaQF5AWM9E3m84yUfKiBOD3DkqddBTwhTOyr2Le8J8oCvrsgvLGHpOqdSoFwDinakLzL1qEYZeWXC470TucQp2aou5gsYRP5yj0ZxDNpM+WiTnK+sle9OWcrpAqsdZMsKCi61qickWAJ9kG3i3orzr82SWp4j630ZXOaOyIeoNwnRB+QfetCQuVYc4ZokD6Nw1tlf/4qGisFNXVPjMze140cqTXSTAiCE6e8fPkvrFU7hgKQ/BFuuAquXg1ivMFsbOLBYfs0LrkqxmjChTuIJfGt0cSw3hMM2eJE6DG9hqRwEgeihhi46Rr0ht5idRbjYY49C2WvJyQp9B7v4XDcSOH7A9fBwnnslnzjs3OaiL44BswDPYyVO5EZ2zvoOztPLdnJ0c1Le0YOOvMGhIXfydUnu6K3kYnI3VHxDzCH6gGuvh/Gnub7+aCPyUqv/y3d7zpAqJPlCB2C+gRoIumnU+1yijh6stwP07ZkKGe9bE/KE0v7QZrOaWgCnRrrfGlhYjNh1+Uhna9lMKKbTBLrWOtsa0v/FXfzgP0jZol4iZx8MUEFfpJEiKIvCeI1AzLNyjf7viang134VHWsN7tEdbt3bii4Q/QgpRtZIftD3dgzED1tvTYLGOACNXjKUQ8cPP/XV6kcsikszAZ2QUf7oFTddoHQ3CsnNtST6OeGxDe+HjOSgoZI1AOttrZ39CIwulXzUHE4CM9bk15gJnSSYa91pyiGjGUnP0zSKVOuDIyuJmi+XOC3tbk7AWQNoJSa+U6mSsOWaxwSg5o3E4TP3tqDz7gFUtXYN9ETKmsPYzJmgAmZVAXmtmIV1OAWBZu+ecKfqzwJOwMB6YDdZJ7z79itwahwya09qG/52tk2ZOAsibeBLFyHmnLVu1veCXs5PVPYczXPL/euek3GD7IkNqe6Rosyc2kagvXwDgm60ZeqoHW9CPDQLZO4tbmO6JaCZD4UxPXHF3oJ421tyynT1HKCT7O+FnqaDz9kCqJ7qa6nIQaanzIR4Encue6ppa3jrZlfejMRtYngS4nEiptTT68xn22Z1G43tuPrgzgVBT8Bo6/akr0rjF8CKuXByUoCj1DQqwaDdvbeUaNuPBioDjUrF2oAeTzpSa2wDHZpXyDcK3QXjaEbzVnvM1E4CCYBJWHGlcbUNgkJsp5KcVaoCPflQ0V4i4kNJ1rCIx1vmD3MBUz3/kqBitkkNgzmEuYPOaTxTUvkJXB/81Kd3BNxbgIhox8ShBDlEfGJzfvW4eGh9hbyy0Wv6v/iVLCtGV6OA/6JIqs+QVSwqY2/nkJcTzUreLnn3MO1PCNUtaMn4H8EgCjakj0Q1rDpx9UBaELBJPok8XN9hjSezK+6lF5RxSzCKGW32ilRwynByKLHoBL+YQtyrwIXQ1wc6PGcboDsnExVxDqdTCEbzEJtKONrSnm6byIdyZ93gaePUHJiaDgcBxWgfPOSgbrTT3erIKr6PetBInm5B27Mn9j/Y/Vy90MbuVpKJsfO+7V6/VTehD+rMxtbqEAovWKravUkgyRTRKLHky/FWp9UylJOg+ZUcfGRGEaLwFcQHhQI6sXzj97zkhtgtzXZYyz93KmZVg+9HW+NhcR5TnFtKpt69C4D4yHTl2pc6vCbh42onExj21iMP6cIlkmaRroTeUSTs/Jkpw7Ko4mirL/8D1m/lzo8cHLhwpv8ob6DLX1kirF3plwG9m45+y7gzLYmRdvgLBP4GO3+LaMj5ml4ww/Eu2j9MMm+5McQEm+H5Nw6jV71AnAteDoUK1qhoYrYfgwcC9e8NGWuKdvyx3h/dtnhOpPyxUN3dpQzfw2npQe0+qL/dRjvDguiP20oGIogjgbR+WIfoJBHFzVDutwI/JrcmIstLz8urf+KNhKwGBOHsi3QeafDQ79gmhGJE+A7RH8UvIJDEjIX5W7K4nK/B2oMdE3ZP15RyrOwikk0iyjF4myGURgl2kHeWtqvZK1ZkNieHA2c/nibK7CWnn+pqQrjL2QJvsOv6UapwgE2hRh6q9mGQMlJIuULvUoanwxsHPxOnihm9UnJJnMfK5+4tIw4/pdyTaQ3LkKiVh2IfpxXViapTxVdr8OJUVHdPaAAqDrx9qbYhavKmoeC9OBZYoXz8ruaAK9PT68IxBDD9NgTUrCZ27C5tB5diJajCezR7LvZeCV1y4ccye60xS1DtNJdgbUjCkOnKfQN9Nzd3sOa6lV5wjrxBcaLabdCVQ0lIpigxDk+9wQjbo0kNlKpwZ+e0+GzwEBcdxLCT9RDYQUwraoGlID/KDy/s6S4U/it9bpW4R2smRwSY0066N2GiJc4audjfCACHyYP/v8HXWgggqNGETSaBAArnW4EfdMMxf1v1OMW/DFtUeJDKJs3L0nHGx3Fh5IJbPRiTVFrKPUqr2UhDDGkRMTuarp9jqBRpC59J2iMHXXwvpZkod8R90z5t3gBsyC4ftIS8K0cfrropK8Ovh/IBOwJE/KAuXNdg63Rr9ynzlye24vWBEbhs2GrXw8pGQNe7TuRUfDs8V1rLNrNd/Hxo4r5RTF21HtTS6EvLoGwjNJHeZckt4rWApY5fZQ1pW8wZtNUAPI3nUl1TrOo0jNyGJ295kDv2pxe6EZkq5046xezdJIPM2yCS3Utx1XuevX6h1Xi+9q36E9cQYlhaVgk4/bzju5/JgyXuYqc0Oq5TnrtokXZoZ29HTOpq7bL62LCz0xy57fGgLjHri9Bkuw7o4AkaFk5ZAQQiE8ibxOAkuqo6dgBMJjO5NWqjd2pf3a6YQq/JaMiB/rTooWjOimjjDaJreG33cBb/Qx/YvaoLPgE34IL/qtcU0ANVw1N8ju7mKzOyujIAmmaMjGGydamWIiOe1tDcD48pkVigyNP8R4ZBqaIW7+dEroAP6tMkBhgkWqW942OoVnIQq9NMbS4yUyFOEgnlXnLV//tqB3HsoY84yhudjirIIJqYrnWe4zbXKnPZkh5fdD/hA+dEiqcsuUmE6LVeZtCtuE+2MSPwtYQ2kXgHiocSDH/KBkoiplDxDpNtYdvY8SmrpH64vBwPVPpGFgRR2LZXVyZi4rDXucjzovxOJS9LwmHhiRhrdYVN7DFT3VfuvNNTnIgFO7NP2jssC+2Uuo3Sc7ik2aU1NRZIBZaRD2F/wKk+wadNMr6MqvX9x7gQOra0yCbmAZRylU0lRK9PkupFV2CUIwbsgJr1WE4y7Gjc1QtdgEo2FDl9X85zBSkTQ6FkFwt4gLgiGFoq+Y5QCLwf8M4WrNZxWqKGPMmOvXcipwFaLq4yIlrMy2m3It8M3rMzh44bL1LP0iBfck5VTBlDegVWkxIDQHctfDWFFiqTP9qL5AvbNbedweJ7Zm6CxdyQUxxOr1zuO7SKs8vNvQz3f4unB96DBlvkBXVyVoS5s2gf4FRuH0IFvkgOaa+JAuduEij5tnElCxOmol+uK/w9fnv2jzij5V0fOTDQdM7erCGuBIAsOnt5sWBIdq2d9Hby9VBXuakNFbAvaVYAG2TrYuPnAKPnugevyzcAzFsa20tdzEgLDlexkG7tyhMs8S9rtgE5fbwStfibq0IjS/gHE3T9e7c9KrszATyY4I5ksRUSJ1vH1VgGrF3cObdMSR53ePLEt+TFUOKqtwiz6L4C4Z6bBZ6LdHJqZP5aPhnBCDbyFbVrTASWsxCS/upMSqTSf6JtIqwsPELZSCXRN5EBdw5x9HwfD8gXmoAp2OoOIQZwkaD9d/ZcGUZr6XPNZnA0ZqQAYYyysr2lZJ8+Qrho69yPYnlGNYCLCJ+li9tooJlp1sOijfpwLz1xfJMBvqFXdseu2veHH7UW2MT7V8nVyXryr2Qz3/9b1ahvm9YWnX+bRX8ZWA8joMbVcXhUIfqe28mG2oc9Oj1yHeEMLJ7orbv8ScTORtapoHeKeC9lxioviTRkW0kTWMe9O6qQ9WpiL04Of5kmSRGg/7kwecMquVwtEu561f0PNxN3eCJUGEFXT58PC4Q6yK4l1MHeyEfSn1j5kOfjTsmcKs2C96c+KWjW5/9TtiD32oB7WiZy+O3dhpmhmaVeXh5vWxeHMmq3DovTKC3rWNdunUNvO81UTzvYAf3jPAtP3fuQAASbPAd9z4qa7RVJNbXOOVRWSQIFjxHUzQFG85fvikLHxbGytroEeU0cC1KRhrW+t+V26pat+J8VWtAU8RoJScWmYUJWQ50YajELIwahAxCy4tQNqLnPO+KJP8YRM3PA8T5nlH7Tm6tA2Km/vtu8UGTPWzZe8QdX39jb/QJdv40phlV1s2rb59TXgC95zqtLSqj6iHb6RiKgYEvB0n+z4LSfDMEj4KClgvcCVV5A6z/Ex3VIZU63kGealEZjJ5q7aAxKYKoP8V09HDbmcCRiQYuWe0FXUDcSDyU80suPfoIWZHlm7y/Xtn8xyvPrdiQilgAMLAe3zsMoRsmXFa/xDpAAF06PzeGuwk2uwAuUEi6/5rIDaTd7zb1ttAite7FW8A43roe5Rz/sHFPPdmjOSNar8bLU6XCptHY3HTsTo4L35Kuv54SccrH2S1sFYAlRXPo4YF80Lev9rg6pyJXxB72hyZb0japkCBYKlXhU748WDyUStUtEIStsB8U8KOj/l3KKCWb4p5lOWL9rIxo/cAKF740LLHc6fDSM6RPiSv4T1IzA+1ETrJ2DqWixBv9C+YiU0OyLzykBnAf8m19ezIfN6iMtDX2aH1L4VQiObVF9A0tDrMPd9pYA6lwfML49msRsby1/1uY9ljywW9h8lvVvlqEzi0Ckx1U3eAtkQyQbisCTrgLSj+5GKLJs5+XTQeD3oRyGy/fTKUTiienGMCgcpMiYFaSwRCZm8LtrLD62YRrTv9yaMHjVB+MZr1hvstkalwdAQDOR7aAeH39A3tQatjGz5h3E3cCPsb3huZ7PW62kqzR7vEAKTT43aQ2A/swOhjlnttS+YaInERI6+cSU+iFnR90rF4yO8j2JO5DWObo5EXKKYF50WFtNE5X9FbQkwlok3Ek9T3JwYuvnRWDmdvjfby0OKwFvxWNix19Uxe1NpyzEAqYcJMRZFTWASblcUwGA6dhIIpPmTXjuqKgMRbU/nucuS8s962sPX8OCZTTu7mcCY/tGz7QOa3iEuBRNilp2DLH75cXf+jLn+vZQTIJvh5tvCDj1aAjdWUwAmHpCZRBoyszehuJ3i3Ygp/PEJQxjN1bz54qG7qI1ZUsDSsXKr19IMV+Vp7CcaLSYWfEV1yQ+YVrvLKMVH1Gq+P3S3ipcc140F9vlMhgBMZnCweSkrN1GC4eHrjrkqDZIpsZslwefKrcbbWDN6BSWB8HMNUESEiwHSLcbwQHEXMbOq0dVgYFP++KLKyGe4mLOMDo85RCjsiQ0eRpmj8MZ2TSFg+3PRNlANR4uMLgZDFVcyPm3WNWKcmIAaOUCb0HBuYVjMl4kONyfXVcIhMpVRPzQQCUuojWI28ij2qDH3/zuzfBgwAjt9ed/p5Kx5fMQcDSWA/7YiSLiFYL1ISH2Kd/g0n7bTVR45pLHIVX3zNF2pHCH3aNZXuAAYo6IPnG9ceRwZe9NqyVn5Qn8pRQr9MBnY8DxmPMZ0ALVTanFzFdB50tTSW4hfHiIqDAsdyFbgroboXf8j5KreIohBMDsOgNodpml9zV0MHp6fmmXOLqwKBtTOpBDBMDhR3tjbBt1DRXNM/eyPIszE4wY8SW/noxDwNNA5FFnWT/DdTBMIoG+zthGIlDBnrjnKE/I6CsObjZzlBpbXLn/My8yjPnAAQhq3iqWn99yhCz4L22Po9t/9tbtRLImdU8OnSjvFIrNTx5L17MLGnIoMHPM0L12NkCif6mFdaQNLGOeGPRlIah8ri5z36NLNsbRMmZF0WZhvW8QqsdUpS1xSRJGordKuwgbIScsNwOSrqtGnru6aFW7kpsOdFUJ6WjoKF9vEj9JahEpyERvpyAVyaMD8kTQjkwY/Fyk0jIpExtcfRfJxID+4/SJr6OyDTpXrqB1KFDI6FkO0+GdCTo/gJDpHCMCAy6CbXDTU9+XasEsNaNauT1HAcYRjl63ctghtRxsLSanxiEHEqwKRc5Y+IvrJe2P9QCgAAAAA==');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .hero-clean::before {
      content: '';
      position: absolute;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      top: -50%;
      left: -50%;
      animation: pulse 15s infinite linear;
    }

    @keyframes pulse {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .hero-content {
      max-width: 800px;
      padding: 0 20px;
      z-index: 2;
    }

    .hero-clean h1 {
      font-size: 3.8rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      letter-spacing: -0.5px;
    }

    .hero-clean p {
      font-size: 1.4rem;
      margin-bottom: 2.5rem;
      text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    .hero-clean .btn {
      padding: 14px 35px;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 50px;
      transition: var(--transition-smooth);
      box-shadow: var(--shadow-medium);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .hero-clean .btn-light {
      background: rgba(255, 255, 255, 0.9);
      color: var(--main-blue);
      border: none;
    }

    .hero-clean .btn-light:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-heavy);
    }

    .hero-clean .btn-light::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      transition: left 0.7s;
      z-index: -1;
    }

    .hero-clean .btn-light:hover::before {
      left: 100%;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(10px);
      padding: 12px 25px;
      border-radius: 50px;
      margin-bottom: 2rem;
      border: 1px solid rgba(255,255,255,0.2);
      font-weight: 500;
    }

    /* === COMPANY DESCRIPTION === */
    .company-description {
      background: linear-gradient(135deg, #f8fafc, #e2e8f0);
      position: relative;
      overflow: hidden;
    }

    .company-description::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 400px;
      height: 400px;
      background: linear-gradient(135deg, var(--accent-teal), var(--dark-teal));
      border-radius: 50%;
      opacity: 0.1;
      z-index: 1;
    }

    .company-description::after {
      content: '';
      position: absolute;
      bottom: -30%;
      left: -10%;
      width: 300px;
      height: 300px;
      background: linear-gradient(135deg, var(--main-blue), var(--dark-teal));
      border-radius: 50%;
      opacity: 0.05;
      z-index: 1;
    }

    .company-description .container {
      position: relative;
      z-index: 2;
    }

    /* === SECTION TITLE === */
    .section-title {
      font-weight: 700;
      color: var(--main-blue);
      position: relative;
      margin-bottom: 3rem;
      font-size: 2.5rem;
    }

    .section-title::after {
      content: "";
      width: 80px;
      height: 5px;
      background: linear-gradient(90deg, var(--accent-teal), var(--dark-teal));
      display: block;
      margin: 15px auto 0;
      border-radius: 3px;
    }

    .text-start .section-title::after {
      margin: 15px 0 0;
    }

    /* === SERVICE ICONS === */
    .service-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--accent-teal), var(--dark-teal));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: var(--shadow-light);
    }

    .service-icon:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: var(--shadow-medium);
    }

    .service-icon i {
      font-size: 2rem;
      color: white;
    }

   /* === PROGRAM CARDS - FIXED IMAGE === */
.program-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: var(--shadow-light);
    transition: var(--transition-smooth);
    height: 100%;
    position: relative;
    display: flex;
    flex-direction: column;
}

.program-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-heavy);
}

.program-card-img-container {
    height: 200px;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
}

.program-card-img {
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.program-card:hover .program-card-img {
    transform: scale(1.05);
}

.program-card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.program-card-title {
    color: var(--main-blue);
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: 1.1rem;
    line-height: 1.4;
    min-height: 3.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.program-card .card-text {
    flex-grow: 1;
    margin-bottom: 1.5rem;
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.btn-program {
    margin-top: auto;
    background: linear-gradient(135deg, var(--main-blue), var(--dark-teal));
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition-smooth);
    text-decoration: none;
    text-align: center;
    display: block;
    width: 100%;
}

.btn-program:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-medium);
    color: white;
}
    /* === BENEFIT CARDS === */
    .benefit-card {
      height: 100%;
      padding: 2rem 1.5rem;
      text-align: center;
      border-radius: 15px;
      box-shadow: var(--shadow-light);
      transition: var(--transition-smooth);
      background: white;
      position: relative;
      overflow: hidden;
    }

    .benefit-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-medium);
    }

    .benefit-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--accent-teal), var(--dark-teal));
    }

    .benefit-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      background: linear-gradient(135deg, var(--accent-teal), var(--dark-teal));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-light);
      transition: var(--transition-smooth);
    }

    .benefit-card:hover .benefit-icon {
      transform: scale(1.1);
    }

    .benefit-icon i {
      font-size: 2rem;
      color: white;
    }

    .benefit-card h5 {
      color: var(--main-blue);
      margin-bottom: 1rem;
      font-weight: 600;
    }

    /* === GALLERY STYLES - CAPTION DIHAPUS === */
    .gallery-section {
      padding: 80px 0;
      background-color: #f8f9fa;
    }
    
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 25px;
      margin-top: 40px;
    }
    
    .gallery-item {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow-light);
      transition: var(--transition-smooth);
      height: 280px;
    }
    
    .gallery-item:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-heavy);
    }
    
    .gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s;
    }
    
    .gallery-item:hover img {
      transform: scale(1.08);
    }
    
    /* CAPTION OVERLAY DIHAPUS */
    .gallery-overlay {
      display: none;
    }

    /* === FAQ STYLES === */
    .faq-section {
      padding: 80px 0;
      background-color: #fff;
    }
    
    .accordion-button:not(.collapsed) {
      background-color: rgba(45, 212, 191, 0.1);
      color: var(--main-blue);
      font-weight: 600;
      box-shadow: none;
    }
    
    .accordion-button:focus {
      box-shadow: 0 0 0 0.25rem rgba(45, 212, 191, 0.25);
      border-color: var(--accent-teal);
    }
    
    .accordion-button::after {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%231B4B7A'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    
    .accordion-button:not(.collapsed)::after {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232DD4BF'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    /* === TESTIMONIAL SECTION === */
    .testimonial-section {
      padding: 80px 0;
      background: linear-gradient(135deg, var(--light-blue), #ffffff);
    }

    .testimonial-card {
      background: white;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: var(--shadow-light);
      transition: var(--transition-smooth);
      height: 100%;
      position: relative;
    }

    .testimonial-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-medium);
    }

    .testimonial-card::before {
      content: '"';
      position: absolute;
      top: 10px;
      left: 20px;
      font-size: 5rem;
      color: rgba(45, 212, 191, 0.2);
      font-family: Georgia, serif;
      line-height: 1;
    }

    .testimonial-text {
      font-style: italic;
      margin-bottom: 1.5rem;
      position: relative;
      z-index: 1;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
    }

    .testimonial-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      overflow: hidden;
      margin-right: 15px;
    }

    .testimonial-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .testimonial-info h6 {
      margin-bottom: 0;
      color: var(--main-blue);
      font-weight: 600;
    }

    .testimonial-info p {
      margin-bottom: 0;
      font-size: 0.9rem;
      color: var(--dark-teal);
    }

    /* === JADWAL PELATIHAN === */
    .jadwal-section {
      padding: 80px 0;
      background-color: #f8f9fa;
    }

    /* Jadwal Table Styles */
    .jadwal-table {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow-light);
    }

    .jadwal-table thead {
      background: linear-gradient(135deg, var(--main-blue), var(--dark-teal));
      color: white;
    }

    .jadwal-table th {
      border: none;
      padding: 1rem;
      font-weight: 600;
      text-align: center;
    }

    .jadwal-table td {
      padding: 1rem;
      vertical-align: middle;
      text-align: center;
    }

    .jadwal-table tbody tr {
      transition: all 0.3s ease;
    }

    .jadwal-table tbody tr:hover {
      background-color: var(--light-blue);
      transform: translateY(-2px);
    }

    /* Program Badge */
    .program-badge {
      background: linear-gradient(135deg, var(--accent-teal), var(--dark-teal));
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
    }

    /* Date Cell */
    .date-cell {
      min-width: 120px;
    }

    .date-main {
      font-weight: 600;
      color: var(--main-blue);
      font-size: 1rem;
    }

    .date-range {
      font-size: 0.85rem;
      color: #6c757d;
      margin-top: 5px;
    }

    /* Location Badge */
    .location-badge {
      background: var(--light-blue);
      color: var(--main-blue);
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    /* Status Badge */
    .status-badge {
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
    }

    .status-open {
      background: rgba(40, 167, 69, 0.15);
      color: #28a745;
    }

    .status-full {
      background: rgba(220, 53, 69, 0.15);
      color: #dc3545;
    }

    /* Button Daftar */
    .btn-daftar {
      background: linear-gradient(135deg, var(--main-blue), var(--dark-teal));
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      font-size: 0.9rem;
    }

    .btn-daftar:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(27, 75, 122, 0.3);
      color: white;
    }

    .btn-daftar.disabled {
      background: #6c757d;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    /* Filter Section */
    .filter-section {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Empty State */
    .empty-state {
      color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .jadwal-table {
        font-size: 0.9rem;
      }
      
      .jadwal-table th,
      .jadwal-table td {
        padding: 12px 8px;
      }
      
      .program-badge,
      .status-badge,
      .location-badge {
        font-size: 0.75rem;
        padding: 6px 10px;
      }
    }

    /* === WHATSAPP FLOAT === */
    .whatsapp-float {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 1000;
    }

    .whatsapp-float a {
      background: linear-gradient(135deg, #25D366, #128C7E);
      color: white;
      width: 65px;
      height: 65px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      box-shadow: var(--shadow-medium);
      animation: pulse 2s infinite;
      transition: var(--transition-smooth);
    }

    .whatsapp-float a:hover {
      transform: scale(1.1);
      box-shadow: var(--shadow-heavy);
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
      }
      50% {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
      }
      100% {
        transform: scale(1);
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
      }
    }

    /* === FOOTER === */
    footer {
      background: linear-gradient(135deg, var(--main-blue), #0f3a63);
      color: #ddd;
      position: relative;
      overflow: hidden;
    }

    footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--accent-teal), var(--dark-teal));
    }

    footer a {
      color: #fff;
      text-decoration: none;
      transition: var(--transition-smooth);
    }

    footer a:hover {
      color: var(--accent-teal);
    }
    
    .footer-logo {
      max-width: 200px;
      margin-bottom: 20px;
    }
    
    .social-icons a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      text-align: center;
      line-height: 40px;
      margin-right: 12px;
      transition: var(--transition-smooth);
    }
    
    .social-icons a:hover {
      background-color: var(--accent-teal);
      transform: translateY(-3px);
    }
    
    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 20px;
      margin-top: 40px;
    }

    /* === RESPONSIVE === */
    @media (max-width: 992px) {
      .hero-clean h1 {
        font-size: 3rem;
      }
      
      .section-title {
        font-size: 2.2rem;
      }
    }

    @media (max-width: 768px) {
      .hero-clean {
        height: 70vh;
        background-attachment: scroll;
      }
      
      .hero-clean h1 {
        font-size: 2.5rem;
      }
      
      .hero-clean p {
        font-size: 1.2rem;
      }
      
      .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      }
      
      .navbar-custom .nav-link {
        margin: 5px 0;
        text-align: center;
      }
    }

    @media (max-width: 576px) {
      .hero-clean {
        height: 60vh;
      }
      
      .hero-clean h1 {
        font-size: 2rem;
      }
      
      .hero-clean p {
        font-size: 1rem;
      }
      
      .section-title {
        font-size: 1.8rem;
      }
      
      .hero-badge {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="https://th.bing.com/th/id/R.bb77d407c55bdaa0fd5ff7609e12aaf3?rik=%2fMn6cUMbowsNLQ&riu=http%3a%2f%2fomegasafetyindonesia.com%2fassets%2fimg%2flogo4.png&ehk=WRmiH1Ry%2bW7Kob3elPeexPudtl3IlaKOysiqZqujMRY%3d&risl=&pid=ImgRaw&r=0" 
                 alt="PT Omega Safety Indonesia Logo"
                 class="me-2">
            <div class="d-flex flex-column">
                <span class="fw-bold" style="color: var(--main-blue); font-size: 1.1rem; line-height: 1.1;">
                    PT. Omega Safety
                </span>
                <span style="color: var(--dark-teal); font-size: 0.8rem; line-height: 1;">
                    Indonesia
                </span>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="#program">Program</a></li>
                <li class="nav-item"><a class="nav-link" href="#manfaat">Manfaat</a></li>          
                <li class="nav-item"><a class="nav-link" href="#jadwal">Jadwal</a></li>
                <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            </ul>
        </div>
    </div>
</nav>

  <!-- Hero Clean -->
  <section class="hero-clean">
    <div class="hero-content" data-aos="fade-up">
      <div class="hero-badge">
        <i class="bi bi-shield-check"></i>
        <span>Pelatihan K3 Terpercaya Sejak 2008</span>
      </div>
      <h1 class="fw-bold">Pelatihan & Sertifikasi K3 Umum</h1>
      <p class="lead">Tingkatkan kompetensi K3 Anda dengan program pelatihan berkualitas dari lembaga terakreditasi</p>
      <a href="#program" class="btn btn-light">Lihat Program</a>
    </div>
  </section>

  <!-- Company Description -->
  <section id="tentang" class="py-5 company-description">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6" data-aos="fade-right">
          <h2 class="section-title text-start">Tentang PT Omega Safety Indonesia</h2>
          <p class="lead mt-4" style="color: var(--main-blue);">
            Lembaga pelatihan Keselamatan dan Kesehatan Kerja (K3) terdepan di Indonesia dengan pengalaman lebih dari 15 tahun dalam mengembangkan kompetensi profesional K3.
          </p>
          <p class="mb-4">
            Kami berkomitmen memberikan pelatihan berkualitas tinggi yang sesuai dengan standar nasional dan internasional. 
            Dengan instruktur bersertifikat dan fasilitas modern, kami telah melatih lebih dari 50.000 profesional di seluruh Indonesia.
          </p>
          
          <!-- Layanan K3 -->
          <div class="row g-3 mt-4">
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <div class="service-icon" style="width: 60px; height: 60px; margin-right: 15px; margin-bottom: 0;">
                  <i class="bi bi-shield-check"></i>
                </div>
                <div>
                  <h6 class="mb-1" style="color: var(--main-blue);">Pelatihan K3 Umum</h6>
                  <small class="text-muted">Sertifikasi resmi Kemenaker</small>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <div class="service-icon" style="width: 60px; height: 60px; margin-right: 15px; margin-bottom: 0;">
                  <i class="bi bi-people-fill"></i>
                </div>
                <div>
                  <h6 class="mb-1" style="color: var(--main-blue);">Pelatihan Ahli K3</h6>
                  <small class="text-muted">Program spesialisasi lanjutan</small>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <div class="service-icon" style="width: 60px; height: 60px; margin-right: 15px; margin-bottom: 0;">
                  <i class="bi bi-building"></i>
                </div>
                <div>
                  <h6 class="mb-1" style="color: var(--main-blue);">Konsultasi K3</h6>
                  <small class="text-muted">Audit dan implementasi SMK3</small>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <div class="service-icon" style="width: 60px; height: 60px; margin-right: 15px; margin-bottom: 0;">
                  <i class="bi bi-gear-fill"></i>
                </div>
                <div>
                  <h6 class="mb-1" style="color: var(--main-blue);">Pelatihan Teknis</h6>
                  <small class="text-muted">Operator alat berat & crane</small>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6" data-aos="fade-left">
          <div class="row g-4">
            <div class="col-6">
              <div class="text-center p-4 benefit-card">
                <h3 class="fw-bold" style="color: var(--accent-teal);">50K+</h3>
                <p class="mb-0">Peserta Terlatih</p>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-4 benefit-card">
                <h3 class="fw-bold" style="color: var(--accent-teal);">15+</h3>
                <p class="mb-0">Tahun Pengalaman</p>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-4 benefit-card">
                <h3 class="fw-bold" style="color: var(--accent-teal);">98%</h3>
                <p class="mb-0">Tingkat Kelulusan</p>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-4 benefit-card">
                <h3 class="fw-bold" style="color: var(--accent-teal);">500+</h3>
                <p class="mb-0">Perusahaan Klien</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Program Cards -->
  <section id="program" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center section-title" data-aos="fade-up">Pilih Program Pelatihan</h2>
        <p class="text-center mb-5" data-aos="fade-up" data-aos-delay="100">Program pelatihan K3 berkualitas dengan sertifikasi resmi yang diakui secara nasional</p>
        
        <div class="row g-4 justify-content-center">
            <?php if (!empty($program_list)): ?>
                <?php 
                $delay = 100;
                foreach ($program_list as $program): 
                    // Cek dan atur path gambar yang benar
                    $gambar_path = '';
                    if (!empty($program['gambar'])) {
                        $gambar_path = 'asset/gambar/' . $program['gambar'];
                    } else {
                        $gambar_path = 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                    }
                    
                    // Potong deskripsi jika terlalu panjang
                    $deskripsi = $program['deskripsi'] ?? 'Program pelatihan K3 berkualitas dengan sertifikasi resmi';
                    if (strlen($deskripsi) > 120) {
                        $deskripsi = substr($deskripsi, 0, 120) . '...';
                    }
                ?>
                <div class="col-sm-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="card program-card h-100">
                        <div class="program-card-img-container">
                            <img src="<?php echo $gambar_path; ?>" 
                                class="program-card-img" 
                                alt="<?php echo htmlspecialchars($program['nama_program']); ?>"
                                onerror="this.src='https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                        </div>
                        <div class="card-body program-card-body d-flex flex-column">
                            <h5 class="card-title program-card-title"><?php echo htmlspecialchars($program['nama_program']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($deskripsi); ?></p>
                            <a href="./front-program/detail1.php?id=<?php echo $program['id']; ?>" class="btn btn-program mt-auto">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                <?php 
                $delay += 100;
                endforeach; 
                ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="empty-state py-5">
                        <i class="bi bi-collection display-1 text-muted"></i>
                        <h4 class="mt-3">Belum Ada Program Tersedia</h4>
                        <p class="text-muted">Silakan hubungi kami untuk informasi program pelatihan.</p>
                        <a href="#kontak" class="btn btn-primary mt-3">
                            <i class="bi bi-telephone me-1"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
  </section>

  <!-- Jadwal Section -->
  <section id="jadwal" class="jadwal-section">
    <div class="container">
      <h2 class="text-center section-title" data-aos="fade-up">Jadwal Pelatihan 2025</h2>
      <p class="text-center mb-5" data-aos="fade-up" data-aos-delay="100">Pilih jadwal yang sesuai dengan kebutuhan Anda</p>
      
      <!-- Filter Section -->
      <div class="filter-section mb-4" data-aos="fade-up" data-aos-delay="150">
        <div class="row g-3">
          <div class="col-md-4">
            <select class="form-select" id="filterProgram">
              <option value="">Semua Program</option>
              <?php
              $program_query = "SELECT * FROM program WHERE status = 'aktif' ORDER BY nama_program ASC";
              $program_result = mysqli_query($conn, $program_query);
              while ($program = mysqli_fetch_assoc($program_result)) {
                echo "<option value='{$program['id']}'>{$program['nama_program']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-select" id="filterLokasi">
              <option value="">Semua Lokasi</option>
              <?php
              $lokasi_query = "SELECT DISTINCT lokasi FROM jadwal WHERE status = 'aktif' ORDER BY lokasi ASC";
              $lokasi_result = mysqli_query($conn, $lokasi_query);
              while ($lokasi = mysqli_fetch_assoc($lokasi_result)) {
                echo "<option value='{$lokasi['lokasi']}'>{$lokasi['lokasi']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-select" id="filterStatus">
              <option value="">Semua Status</option>
              <option value="tersedia">Tersedia</option>
              <option value="penuh">Penuh</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="table-responsive" data-aos="fade-up" data-aos-delay="200">
        <?php if (!empty($jadwal_list)): ?>
          <table class="table table-hover jadwal-table">
            <thead>
              <tr>
                <th>Nama Program</th>
                <th>Tanggal Pelatihan</th>
                <th>Lokasi</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jadwal_list as $jadwal): 
                $kuota_tersisa = $jadwal['kuota'] - $jadwal['terisi'];
                $status = $kuota_tersisa > 0 ? 'tersedia' : 'penuh';
                $status_text = $kuota_tersisa > 0 ? 'Tersedia' : 'Penuh';
                $status_class = $kuota_tersisa > 0 ? 'status-open' : 'status-full';
                
                // Format tanggal
                $tanggal_mulai = date('d M Y', strtotime($jadwal['tanggal_mulai']));
                $tanggal_selesai = date('d M Y', strtotime($jadwal['tanggal_selesai']));
                $durasi = (strtotime($jadwal['tanggal_selesai']) - strtotime($jadwal['tanggal_mulai'])) / (60 * 60 * 24) + 1;
              ?>
              <tr class="jadwal-row" 
                  data-program="<?php echo $jadwal['program_id']; ?>"
                  data-lokasi="<?php echo $jadwal['lokasi']; ?>"
                  data-status="<?php echo $status; ?>">
                <td>
                  <span class="program-badge"><?php echo htmlspecialchars($jadwal['nama_program']); ?></span>
                </td>
                <td class="date-cell">
                  <div class="date-main"><?php echo $tanggal_mulai; ?></div>
                  <div class="date-range">
                    s/d <?php echo $tanggal_selesai; ?>
                    <br>
                    <small>(<?php echo $durasi; ?> Hari)</small>
                  </div>
                </td>
                <td class="location-cell">
                  <span class="location-badge">
                    <i class="bi bi-geo-alt me-1"></i><?php echo $jadwal['lokasi']; ?>
                  </span>
                </td>
                <td>
                  <span class="status-badge <?php echo $status_class; ?>">
                    <?php echo $status_text; ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="empty-state text-center py-5">
            <i class="bi bi-calendar-x display-1 text-muted"></i>
            <h4 class="mt-3">Belum Ada Jadwal Tersedia</h4>
            <p class="text-muted">Silakan cek kembali di lain waktu atau hubungi kami untuk informasi lebih lanjut.</p>
            <a href="#kontak" class="btn btn-primary mt-3">
              <i class="bi bi-telephone me-1"></i>Hubungi Kami
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>


  <!-- Testimonial Section -->
  <section id="testimoni" class="testimonial-section">
    <div class="container">
      <h2 class="text-center section-title" data-aos="fade-up">Apa Kata Peserta?</h2>
      <p class="text-center mb-5" data-aos="fade-up" data-aos-delay="100">Testimoni dari alumni yang telah mengikuti pelatihan di PT Omega Safety Indonesia</p>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
          <div class="testimonial-card">
            <p class="testimonial-text">"Pelatihan K3 Umum di Omega Safety sangat bermanfaat bagi karir saya. Materi disampaikan dengan jelas dan instruktur sangat berpengalaman."</p>
            <div class="testimonial-author">
              <div class="testimonial-avatar">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Ahmad Rizki">
              </div>
              <div class="testimonial-info">
                <h6>Ahmad Rizki</h6>
                <p>Safety Officer, PT Jaya Konstruksi</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
          <div class="testimonial-card">
            <p class="testimonial-text">"Sertifikasi Ahli K3 dari Omega Safety membantu saya mendapatkan promosi di perusahaan. Materi lengkap dan sesuai kebutuhan industri."</p>
            <div class="testimonial-author">
              <div class="testimonial-avatar">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sari Dewi">
              </div>
              <div class="testimonial-info">
                <h6>Sari Dewi</h6>
                <p>HSE Manager, PT Global Energi</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
          <div class="testimonial-card">
            <p class="testimonial-text">"Fasilitas pelatihan yang modern dan metode pengajaran yang interaktif membuat saya mudah memahami materi K3 Listrik."</p>
            <div class="testimonial-author">
              <div class="testimonial-avatar">
                <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Budi Santoso">
              </div>
              <div class="testimonial-info">
                <h6>Budi Santoso</h6>
                <p>Electrical Supervisor, PT Power Teknik</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section id="faq" class="faq-section">
    <div class="container">
      <h2 class="text-center section-title" data-aos="fade-up">Pertanyaan yang Sering Diajukan</h2>
      <p class="text-center mb-5" data-aos="fade-up" data-aos-delay="100">Temukan jawaban untuk pertanyaan umum seputar pelatihan K3 di PT Omega Safety Indonesia</p>
      
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="accordion" id="faqAccordion">
            <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                  Berapa lama masa berlaku sertifikat K3 Umum?
                </button>
              </h2>
              <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Sertifikat K3 Umum yang dikeluarkan oleh PT Omega Safety Indonesia memiliki masa berlaku selama 5 tahun. Setelah itu, peserta perlu mengikuti pelatihan penyegaran untuk memperpanjang sertifikat.
                </div>
              </div>
            </div>
            
            <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                  Apakah sertifikat diakui secara nasional?
                </button>
              </h2>
              <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Ya, sertifikat yang kami keluarkan diakui secara nasional dan telah terdaftar di Kementerian Ketenagakerjaan Republik Indonesia. Sertifikat ini berlaku di seluruh wilayah Indonesia.
                </div>
              </div>
            </div>
            
            <div class="accordion-item" data-aos="fade-up" data-aos-delay="400">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                  Berapa jumlah peserta per kelas?
                </button>
              </h2>
              <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Untuk menjaga kualitas pembelajaran, kami membatasi jumlah peserta maksimal 25 orang per kelas. Hal ini memastikan setiap peserta mendapatkan perhatian yang optimal dari instruktur.
                </div>
              </div>
            </div>
            
            <div class="accordion-item" data-aos="fade-up" data-aos-delay="500">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                  Apakah ada jadwal pelatihan di akhir pekan?
                </button>
              </h2>
              <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Ya, kami menyediakan jadwal pelatihan di akhir pekan (Sabtu-Minggu) untuk memudahkan peserta yang bekerja di hari biasa. Silakan hubungi kami untuk informasi jadwal terbaru.
                </div>
              </div>
            </div>
            
            <div class="accordion-item" data-aos="fade-up" data-aos-delay="600">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                  Apakah ada program pelatihan online?
                </button>
              </h2>
              <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Ya, kami menyediakan program pelatihan hybrid (kombinasi online dan tatap muka) untuk beberapa materi teori. Namun, untuk praktik dan ujian tetap dilakukan secara tatap muka sesuai regulasi yang berlaku.
                </div>
              </div>
            </div>
            
            <div class="accordion-item" data-aos="fade-up" data-aos-delay="700">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                  Bagaimana cara mendaftar pelatihan?
                </button>
              </h2>
              <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Anda dapat mendaftar melalui website kami, menghubungi nomor telepon yang tersedia, atau mengunjungi kantor kami langsung. Tim admin kami akan membantu proses pendaftaran hingga selesai.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section id="gallery" class="gallery-section">
    <div class="container">
      <h2 class="text-center section-title" data-aos="fade-up">Gallery</h2>
      <p class="text-center mb-5" data-aos="fade-up" data-aos-delay="100">Lihat dokumentasi kegiatan pelatihan dan sertifikasi K3 di PT Omega Safety Indonesia</p>
      
      <div class="gallery-grid">
        <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
          <img src="https://omegasafetyindonesia.com/login_admin/images/konten/P_0012.jpg" alt="Pelatihan K3 Umum">
        </div>
        
        <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
          <img src="https://th.bing.com/th/id/OIP.wKDgkkOI_d5mKC3BJO83NAHaE8?w=272&h=181&c=7&r=0&o=7&cb=12&dpr=1.5&pid=1.7&rm=3" alt="Praktik Lapangan">
        </div>
        
        <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
          <img src="https://th.bing.com/th/id/OIP.zzG_58Cpwu2OZZc5URpuugHaE8?w=249&h=180&c=7&r=0&o=7&cb=12&dpr=1.5&pid=1.7&rm=3" alt="Sertifikasi">
        </div>
        
        <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
          <img src="https://tse3.mm.bing.net/th/id/OIP.jzSC-W7Ft8kDUC0xqEljTQHaJ4?cb=12&rs=1&pid=ImgDetMain&o=7&rm=3" alt="Diskusi Kelompok">
        </div>
        
        <div class="gallery-item" data-aos="fade-up" data-aos-delay="600">
          <img src="https://th.bing.com/th/id/OIP.WgcbX19Aq3FbKxi1PnOjhAHaHa?w=198&h=198&c=7&r=0&o=7&cb=12&dpr=1.5&pid=1.7&rm=3" alt="Simulasi Darurat">
        </div>
        
        <div class="gallery-item" data-aos="fade-up" data-aos-delay="700">
          <img src="https://th.bing.com/th/id/OIP.L1VXavlmoFFG693-unSJYQHaFj?w=264&h=198&c=7&r=0&o=7&cb=12&dpr=1.5&pid=1.7&rm=3" alt="Fasilitas Pelatihan">
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="kontak" class="pt-5 pb-3">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <img src="https://th.bing.com/th/id/R.bb77d407c55bdaa0fd5ff7609e12aaf3?rik=%2fMn6cUMbowsNLQ&riu=http%3a%2f%2fomegasafetyindonesia.com%2fassets%2fimg%2flogo4.png&ehk=WRmiH1Ry%2bW7Kob3elPeexPudtl3IlaKOysiqZqujMRY%3d&risl=&pid=ImgRaw&r=0" alt="PT Omega Safety Indonesia" class="footer-logo">
          <p class="mt-3">Lembaga pelatihan K3 terpercaya dengan pengalaman lebih dari 15 tahun dalam mengembangkan kompetensi profesional K3 di Indonesia.</p>
          <div class="social-icons mt-4">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
        
        <div class="col-lg-2 col-md-6 mb-4">
          <h5 class="mb-3">Program</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#program">Basic Sea Survival</a></li>
            <li class="mb-2"><a href="#program">Defensice Driving Training K3</a></li>
            <li class="mb-2"><a href="#program">Rigging</a></li>
            <li class="mb-2"><a href="#program">Bosiet</a></li>
            <li class="mb-2"><a href="#program">Semua Program</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <h5 class="mb-3">Tautan Cepat</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#tentang">Tentang Kami</a></li>
            <li class="mb-2"><a href="#program">Program Pelatihan</a></li>
            <li class="mb-2"><a href="#jadwal">Jadwal Pelatihan</a></li>
            <li class="mb-2"><a href="#testimoni">Testimoni</a></li>
            <li class="mb-2"><a href="#faq">FAQ</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <h5 class="mb-3">Kontak Kami</h5>
          <ul class="list-unstyled">
            <li class="mb-3">
              <i class="bi bi-geo-alt-fill me-2"></i>
              Rukan Bilabong Blok RF 1D No. 1 Kab. Bogor Jawa Barat , Indonesia
            </li>
            <li class="mb-3">
              <i class="bi bi-telephone-fill me-2"></i>
              (021) 753-9333
            </li>
            <li class="mb-3">
              <i class="bi bi-whatsapp me-2"></i>
              0812-1842-1190
            </li>
            <li class="mb-3">
              <i class="bi bi-envelope-fill me-2"></i>
              omegasafetyindonesia@gmail.com
            </li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom text-center">
        <p class="mb-0">&copy; 2025 PT Omega Safety Indonesia. All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- WhatsApp Floating Button -->
  <div class="whatsapp-float">
    <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20konsultasi%20mengenai%20pelatihan%20K3%20Umum" target="_blank">
      <i class="bi bi-whatsapp" style="font-size: 1.8rem;"></i>
    </a>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });

    // Navbar scroll effect
    window.addEventListener("scroll", function(){
      let navbar = document.querySelector(".navbar-custom");
      if(window.scrollY > 50){
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });
    
    // Filter functionality for jadwal table
    document.addEventListener('DOMContentLoaded', function() {
      const filterProgram = document.getElementById('filterProgram');
      const filterLokasi = document.getElementById('filterLokasi');
      const filterStatus = document.getElementById('filterStatus');
      const jadwalRows = document.querySelectorAll('.jadwal-row');
      
      if (filterProgram && filterLokasi && filterStatus) {
        function filterTable() {
          const programValue = filterProgram.value;
          const lokasiValue = filterLokasi.value;
          const statusValue = filterStatus.value;
          
          jadwalRows.forEach(row => {
            const programMatch = !programValue || row.getAttribute('data-program') === programValue;
            const lokasiMatch = !lokasiValue || row.getAttribute('data-lokasi') === lokasiValue;
            const statusMatch = !statusValue || row.getAttribute('data-status') === statusValue;
            
            if (programMatch && lokasiMatch && statusMatch) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        }
        
        filterProgram.addEventListener('change', filterTable);
        filterLokasi.addEventListener('change', filterTable);
        filterStatus.addEventListener('change', filterTable);
      }
    });
  </script>
</body>
</html>