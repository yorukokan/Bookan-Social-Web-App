-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 01 Mar 2026, 11:38:29
-- Sunucu sürümü: 8.0.17
-- PHP Sürümü: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `bookan_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `alintilar`
--

CREATE TABLE `alintilar` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `kitap_id` int(11) NOT NULL,
  `alinti_metni` text NOT NULL,
  `paylasim_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `begeni_sayisi` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `alintilar`
--

INSERT INTO `alintilar` (`id`, `kullanici_id`, `kitap_id`, `alinti_metni`, `paylasim_tarihi`, `begeni_sayisi`) VALUES
(5, 2, 109, 'Korkuyordum. Hayallerinde bile korkar mı insan?', '2025-12-06 20:05:39', 0),
(6, 2, 108, 'Ne hissettiğimi hiçbir zaman bilemedim. İnsanlar belli duygular hakkında benimle konuştuklarında ve tanımlama yaptıklarında, sanki ruhumdaki bir şeyi tanımlıyorlarmış gibi hissettim ama bunu daha sonra düşününce, bundan hep kuşku duydum. Kendim olarak hissettiğimin gerçek ben mi, düşüncemdeki ben mi olduğumu hiçbir zaman bilemedim. Kendi oyunlarımda bir karakterim.', '2025-12-06 20:09:09', 0),
(7, 3, 3, 'İnsan tahammül edemeyeceğini zannettiği şeylere pek çabuk alışıyor ve katlanıyor. Ben de yaşayacağım… Ama nasıl yaşayacağım!..', '2025-12-08 21:26:38', 2),
(8, 8, 110, 'aaa', '2025-12-09 10:40:40', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `begeniler`
--

CREATE TABLE `begeniler` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `alinti_id` int(11) NOT NULL,
  `begeni_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `begeniler`
--

INSERT INTO `begeniler` (`id`, `kullanici_id`, `alinti_id`, `begeni_tarihi`) VALUES
(11, 3, 7, '2025-12-08 21:34:48'),
(12, 6, 7, '2025-12-09 09:34:46'),
(13, 8, 8, '2025-12-09 10:40:58');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `favoriler`
--

CREATE TABLE `favoriler` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `kitap_id` int(11) NOT NULL,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `favoriler`
--

INSERT INTO `favoriler` (`id`, `kullanici_id`, `kitap_id`, `eklenme_tarihi`) VALUES
(3, 3, 27, '2025-12-02 12:00:12'),
(6, 5, 3, '2025-12-02 19:32:21'),
(10, 2, 109, '2025-12-06 20:01:47'),
(11, 2, 108, '2025-12-06 20:03:25'),
(12, 2, 104, '2025-12-06 20:06:31'),
(13, 6, 104, '2025-12-09 09:35:55'),
(14, 8, 112, '2025-12-09 10:40:06');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kitaplar`
--

CREATE TABLE `kitaplar` (
  `id` int(11) NOT NULL,
  `kitap_adi` varchar(150) NOT NULL,
  `yazar` varchar(100) NOT NULL,
  `kitap_turu` varchar(50) NOT NULL,
  `kapak_resmi` varchar(255) DEFAULT NULL,
  `aciklama` text,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kitaplar`
--

INSERT INTO `kitaplar` (`id`, `kitap_adi`, `yazar`, `kitap_turu`, `kapak_resmi`, `aciklama`, `eklenme_tarihi`) VALUES
(1, 'Madam Bovary', 'Gustave Flaubert', 'Roman', 'madambovary.jpg', 'Toplum baskısı ve bireysel arzular arasındaki çatışmayı anlatan klasik roman.', '2025-12-02 09:37:43'),
(2, 'Uçurtma Avcısı', 'Khaled Hosseini', 'Roman', 'ucurtmaavcisi.jpg', 'Afganistan’da dostluk ve pişmanlık üzerine dokunaklı bir hikâye.', '2025-12-02 09:37:43'),
(3, 'Kürk Mantolu Madonna', 'Sabahattin Ali', 'Roman', 'kmadonna.jpg', 'Raif Efendi’nin unutulmaz aşk hikâyesi.', '2025-12-02 09:37:43'),
(4, 'Beyaz Zambaklar Ülkesinde', 'Grigory Petrov', 'Roman', 'beyazzambaklar.jpg', 'Bir toplumun dönüşüm öyküsü.', '2025-12-02 09:37:43'),
(5, 'Hayvan Çiftliği', 'George Orwell', 'Roman', 'hayvanciftligi.jpg', 'Totaliter rejim eleştirisi yapan siyasi bir klasik.', '2025-12-02 09:37:43'),
(6, 'Kara Kule: Silahşör', 'Stephen King', 'Fantastik', 'karakule1.jpg', 'Kara Kule evreninin başlangıç noktası.', '2025-12-02 09:37:43'),
(7, 'Percy Jackson: Şimşek Hırsızı', 'Rick Riordan', 'Fantastik', 'percy1.jpg', 'Yunan mitolojisinin modern dünyayla birleştiği seri.', '2025-12-02 09:37:43'),
(8, 'Mistborn: Final Empire', 'Brandon Sanderson', 'Fantastik', 'mistborn.jpg', 'Metallerle büyü yapılan bir dünyada geçen epik macera.', '2025-12-02 09:37:43'),
(9, 'Dracula', 'Bram Stoker', 'Fantastik', 'dracula.jpg', 'Modern vampir kavramını başlatan gotik klasik.', '2025-12-02 09:37:43'),
(11, 'Solaris', 'Stanislaw Lem', 'Bilim Kurgu', 'solaris.jpg', 'İnsanlığın bilinç ve yabancılık temasını işleyen bilim kurgu klasiği.', '2025-12-02 09:37:43'),
(12, 'Ben Bir Gürgen Dalıyım', 'Hasan Ali Toptaş', 'Bilim Kurgu', 'gurgentop.jpg', 'Gerçek ile hayal arasındaki ince çizgide anlatılan bir hikâye.', '2025-12-02 09:37:43'),
(13, 'Marslı', 'Andy Weir', 'Bilim Kurgu', 'marsli.jpg', 'Mars’ta hayatta kalma mücadelesi veren bir astronotun hikayesi.', '2025-12-02 09:37:43'),
(14, 'Zaman Makinesi', 'H.G. Wells', 'Bilim Kurgu', 'zamanmakinesi.jpg', 'Zaman yolculuğu temasını ilk ele alan klasik eser.', '2025-12-02 09:37:43'),
(16, 'Sherlock Holmes: Kızıl Dosya', 'Arthur Conan Doyle', 'Polisiye', 'kizildosya.jpg', 'Sherlock Holmes’un ilk macerası.', '2025-12-02 09:37:43'),
(17, 'On Küçük Zenci', 'Agatha Christie', 'Polisiye', 'onkucukzenci.jpg', 'Gerilim dolu bir ada cinayeti hikayesi.', '2025-12-02 09:37:43'),
(18, 'Symbol', 'Dan Brown', 'Gerilim', 'symbol.jpg', 'Semboller ve gizli örgütler üzerine nefes kesici bir macera.', '2025-12-02 09:37:43'),
(19, 'Gone Girl', 'Gillian Flynn', 'Gerilim', 'gonegirl.jpg', 'Evlilik sırları ve manipülasyon üzerine psikolojik gerilim.', '2025-12-02 09:37:43'),
(22, 'Devlet', 'Platon', 'Felsefe', 'devlet.jpg', 'Adalet ve ideal toplum üzerine temel bir felsefi metin.', '2025-12-02 09:37:43'),
(23, 'Saf Aklın Eleştirisi', 'Immanuel Kant', 'Felsefe', 'safakil.jpg', 'Modern felsefenin temellerini atan büyük eser.', '2025-12-02 09:37:43'),
(24, 'Nietzsche Ağladığında', 'Irvin D. Yalom', 'Felsefe', 'nietzscheagladiginda.jpg', 'Psikanaliz ve felsefeyi birleştiren roman.', '2025-12-02 09:37:43'),
(26, 'Outliers', 'Malcolm Gladwell', 'Kişisel Gelişim', 'outliers.jpg', 'Başarıyı belirleyen görünmez faktörleri anlatır.', '2025-12-02 09:37:43'),
(27, 'Akıllı Yatırımcı', 'Benjamin Graham', 'Kişisel Gelişim', 'yatirimci.jpg', 'Modern yatırım teorisinin temel kitabı.', '2025-12-02 09:37:43'),
(28, 'Mindset', 'Carol Dweck', 'Kişisel Gelişim', 'mindset.jpg', 'Gelişim odaklı düşüncenin gücünü anlatır.', '2025-12-02 09:37:43'),
(29, 'Akış (Flow)', 'Mihaly Csikszentmihalyi', 'Psikoloji', 'akis.jpg', 'Mutluluk ve verimlilik üzerine psikolojik bir başyapıt.', '2025-12-02 09:37:43'),
(30, 'İrade', 'Kelly McGonigal', 'Psikoloji', 'irade.jpg', 'İrade gücünün nasıl geliştirileceğine dair bilimsel bir anlatım.', '2025-12-02 09:37:43'),
(31, 'Cesur Yeni Dünya', 'Aldous Huxley', 'Bilim Kurgu', 'cesur_yeni_dunya.jpg', 'Teknolojinin kontrolündeki bir gelecekte özgürlük arayışını anlatan bir distopya.', '2025-12-03 15:16:51'),
(32, 'Yüzüklerin Efendisi: İki Kule', 'J.R.R. Tolkien', 'Fantastik', 'yuzuklerin_efendisi_iki_kule.jpg', 'Yüzük Kardeşliği’nin dağılmasının ardından yaşanan maceralar.', '2025-12-03 15:16:51'),
(33, 'Etik', 'Aristoteles', 'Felsefe', 'etik.jpg', 'İnsan erdemi ve mutluluğu üzerine temel bir felsefi inceleme.', '2025-12-03 15:16:51'),
(34, 'Kızıl Nehirler', 'Jean-Christophe Grangé', 'Gerilim', 'kizil_nehirler.jpg', 'İki farklı dedektifin ayrı olayları araştırırken kesişen yolları.', '2025-12-03 15:16:51'),
(35, 'Atomik Alışkanlıklar', 'James Clear', 'Kişisel Gelişim', 'atomik_aliskanliklar.jpg', 'Küçük değişikliklerle büyük sonuçlar elde etmenin pratik yöntemleri.', '2025-12-03 15:16:51'),
(36, 'Doğu Ekspresinde Cinayet', 'Agatha Christie', 'Polisiye', 'dogu_ekspresinde_cinayet.jpg', 'Lüks bir trende işlenen cinayeti çözen Hercule Poirot.', '2025-12-03 15:16:51'),
(37, 'Sefiller', 'Victor Hugo', 'Roman', 'sefiller.jpg', 'Adalet, yoksulluk ve sevgi temalarını işleyen Fransız edebiyatı başyapıtı.', '2025-12-03 15:16:51'),
(38, 'Bilinçaltının Gücü', 'Joseph Murphy', 'Psikoloji', 'bilincaltinin_gucu.jpg', 'Bilinçaltının hayatı nasıl etkilediği ve onu olumlu kullanma yolları.', '2025-12-03 15:16:51'),
(39, 'Vakıf', 'Isaac Asimov', 'Bilim Kurgu', 'vakif.jpg', 'Galaktik imparatorluğun çöküşünü önlemeye çalışan bir bilim adamının hikayesi.', '2025-12-03 15:16:51'),
(40, 'Ejderha Mızrağı: Güz Alacakaranlığının Ejderhaları', 'Margaret Weis & Tracy Hickman', 'Fantastik', 'ejderha_mizragi_guz_alacakaranliginin_ejderhalari.jpg', 'Savaş ve büyü dolu bir dünyada geçen epik macera.', '2025-12-03 15:16:51'),
(41, 'Böyle Buyurdu Zerdüşt', 'Friedrich Nietzsche', 'Felsefe', 'boyle_buyurdu_zerdust.jpg', 'Üstinsan kavramını ve ebedi tekerrür düşüncesini işleyen felsefi bir metin.', '2025-12-03 15:16:51'),
(42, 'Görünmez Adam', 'Ralph Ellison', 'Roman', 'gorunmez_adam.jpg', 'Siyah bir adamın Amerika’daki ırkçılık ve kimlik arayışı mücadelesi.', '2025-12-03 15:16:51'),
(43, 'Dört Anlaşma', 'Don Miguel Ruiz', 'Kişisel Gelişim', 'dort_anlasma.jpg', 'Eski Toltek bilgeliği üzerine kurulu kişisel özgürlüğe giden yol haritası.', '2025-12-03 15:16:51'),
(44, 'Kayıp Kız', 'Gillian Flynn', 'Polisiye', 'kayip_kiz.jpg', 'Evlilik yıldönümünde kaybolan eş ve şüpheli kocasının hikayesi.', '2025-12-03 15:16:51'),
(45, 'Duygusal Zeka', 'Daniel Goleman', 'Psikoloji', 'duygusal_zeka.jpg', 'IQ’dan daha önemli olan duygusal zekanın önemini ve geliştirilmesini anlatan bilimsel bir çalışma.', '2025-12-03 15:16:51'),
(46, 'Otostopçunun Galaksi Rehberi', 'Douglas Adams', 'Bilim Kurgu', 'otostopcunun_galaksi_rehberi.jpg', 'Absürt komedi ve bilim kurguyu harmanlayan kült bir seri.', '2025-12-03 15:16:51'),
(47, 'Taht Oyunları: Buz ve Ateşin Şarkısı', 'George R.R. Martin', 'Fantastik', 'taht_oyunlari_buz_ve_atesin_sarkisi.jpg', 'Yedi Krallık’ın tahtı için yapılan acımasız mücadeleleri konu alan epik fantezi.', '2025-12-03 15:16:51'),
(49, 'Cehennem', 'Dan Brown', 'Gerilim', 'cehennem.jpg', 'Dante’nin Cehennem’i üzerinden küresel bir tehdidi konu alan macera.', '2025-12-03 15:16:51'),
(50, 'Etkili İnsanların 7 Alışkanlığı', 'Stephen Covey', 'Kişisel Gelişim', 'etkili_insanlarin_7_aliskanligi.jpg', 'Kişisel ve profesyonel etkinliği artırmak için rehber.', '2025-12-03 15:16:51'),
(52, 'Anna Karenina', 'Lev Tolstoy', 'Roman', 'anna_karenina.jpg', '19. yüzyıl Rus toplumunda aşk, ihanet ve toplumsal ahlak üzerine bir başyapıt.', '2025-12-03 15:16:51'),
(53, 'İnsan Olmak', 'Engin Geçtan', 'Psikoloji', 'insan_olmak.jpg', 'İnsan ilişkileri, yalnızlık ve varoluş üzerine derin psikolojik analizler.', '2025-12-03 15:16:51'),
(54, 'Neuromancer', 'William Gibson', 'Bilim Kurgu', 'neuromancer.jpg', 'Siberpunk türünün öncüsü sayılan, yapay zeka ve sanal gerçeklik temalı eser.', '2025-12-03 15:16:51'),
(55, 'The Witcher: Son Dilek', 'Andrzej Sapkowski', 'Fantastik', 'the_witcher_son_dilek.jpg', 'Geralt of Rivia’nın canavar avcılığı ve kaderle olan mücadelesi.', '2025-12-03 15:16:51'),
(56, 'Şölen', 'Platon', 'Felsefe', 'solen.jpg', 'Aşkın (Eros) doğası üzerine Sokrates ve arkadaşlarının konuşmaları.', '2025-12-03 15:16:51'),
(57, 'Ejderha Dövmeli Kız', 'Stieg Larsson', 'Gerilim', 'ejderha_dovmeli_kiz.jpg', 'Karanlık sırları olan bir ailenin ve bir gazetecinin gizemli kayboluş araştırması.', '2025-12-03 15:16:51'),
(58, 'Sınırlar', 'Henry Cloud & John Townsend', 'Kişisel Gelişim', 'sinirlar.jpg', 'Sağlıklı ilişkiler kurmak için kişisel sınırların önemini anlatan eser.', '2025-12-03 15:16:51'),
(59, 'Kutup Yıldızı', 'Jo Nesbø', 'Polisiye', 'kutup_yildizi.jpg', 'Norveçli dedektif Harry Hole’un karanlık bir vakayı çözme mücadelesi.', '2025-12-03 15:16:51'),
(60, 'Bir İdam Mahkumunun Son Günü', 'Victor Hugo', 'Felsefe', 'bir_idam_mahkumunun_son_gunu.jpg', 'İdam cezasının insanlık dışılığını eleştiren kısa bir eser.', '2025-12-03 15:16:51'),
(62, '2001: Uzay Macerası', 'Arthur C. Clarke', 'Bilim Kurgu', '2001_uzay_macerasi.jpg', 'Yapay zeka, uzay keşfi ve insan evrimi üzerine kült bir eser.', '2025-12-03 15:16:51'),
(63, 'Zaman Çarkı: Dünyanın Gözü', 'Robert Jordan', 'Fantastik', 'zaman_carki_dunyanin_gozu.jpg', 'Kaderinde dünyayı kurtarmak ya da yok etmek olan bir genç etrafında dönen epik seri.', '2025-12-03 15:16:51'),
(64, 'Denemeler', 'Michel de Montaigne', 'Felsefe', 'denemeler.jpg', 'Batı edebiyatında deneme türünün kurucusu sayılan Montaigne’in kişisel düşünceleri.', '2025-12-03 15:16:51'),
(65, 'Kayıp Sembol', 'Dan Brown', 'Gerilim', 'kayip_sembol.jpg', 'Robert Langdon’un Washington D.C.’deki Masonik sırları çözme mücadelesi.', '2025-12-03 15:16:51'),
(66, 'Finansın Psikolojisi', 'Morgan Housel', 'Kişisel Gelişim', 'finansin_psikolojisi.jpg', 'Para, yatırım ve mutlulukla ilgili alışkanlıkları inceleyen bir rehber.', '2025-12-03 15:16:51'),
(67, 'Şeytanın Tohumu', 'Agatha Christie', 'Polisiye', 'seytanin_tohumu.jpg', 'Dedektif Hercule Poirot’nun çözmeye çalıştığı karmaşık bir cinayet vakası.', '2025-12-03 15:16:51'),
(68, 'Küçük Prens', 'Antoine de Saint-Exupéry', 'Roman', 'kucuk_prens.jpg', 'Yetişkinlerin dünyasını eleştiren, dostluk ve sevgi üzerine felsefi bir masal.', '2025-12-03 15:16:51'),
(69, 'Terapi', 'David Lodge', 'Psikoloji', 'terapi.jpg', 'Orta yaş krizi yaşayan bir adamın terapi deneyimlerini mizahi dille anlatan roman.', '2025-12-03 15:16:51'),
(70, 'Kaplan! Kaplan!', 'Alfred Bester', 'Bilim Kurgu', 'kaplan_kaplan.jpg', 'Teleportasyon yeteneğine sahip bir adamın intikam hikayesi.', '2025-12-03 15:16:51'),
(71, 'Yerdeniz Büyücüsü', 'Ursula K. Le Guin', 'Fantastik', 'yerdeniz_buyucusu.jpg', 'Bir büyücü adayının gücünü kontrol etme ve gölge varlıkla yüzleşme hikayesi.', '2025-12-03 15:16:51'),
(72, 'Veba', 'Albert Camus', 'Felsefe', 'veba.jpg', 'Absürdizm ve insan dayanışması üzerine alegorik bir roman.', '2025-12-03 15:16:51'),
(73, 'Kuzuların Sessizliği', 'Thomas Harris', 'Gerilim', 'kuzularin_sessizligi.jpg', 'FBI ajanı adayının bir seri katili yakalamak için Hannibal Lecter’dan yardım alması.', '2025-12-03 15:16:51'),
(74, 'Hızlı ve Yavaş Düşünme', 'Daniel Kahneman', 'Psikoloji', 'hizli_ve_yavas_dusunme.jpg', 'İnsan zihninin iki farklı düşünce sistemini ve karar mekanizmalarını açıklayan eser.', '2025-12-03 15:16:51'),
(75, 'Pür Dikkat', 'Cal Newport', 'Kişisel Gelişim', 'pur_dikkat.jpg', 'Derin çalışma becerisini geliştirme yolları ve dijital dikkat dağıtıcılarla mücadele.', '2025-12-03 15:16:51'),
(80, 'Rüzgarın Adı', 'Patrick Rothfuss', 'Fantastik', 'ruzgarin_adi.jpg', 'Efsanevi bir büyücünün hayat hikayesini birinci ağızdan anlatan modern fantezi.', '2025-12-03 15:16:51'),
(81, 'Kafka Sahilde', 'Haruki Murakami', 'Roman', 'kafka_sahilde.jpg', 'Rüyalar, mitoloji ve bilinçaltı temalarını işleyen mistik bir Japon romanı.', '2025-12-03 15:16:51'),
(82, 'Satranç', 'Stefan Zweig', 'Psikolojik Gerilim', 'satranc.jpg', 'Sürükleyici bir psikolojik gerilim ve insan ruhunun derinliklerine inen klasik bir eser. Kitap, bir gemide satranç tahtası üzerinde yaşanan zihinsel mücadeleyi ve savaşın bir insanın üzerindeki yıkıcı etkilerini konu alır.', '2025-12-04 20:44:49'),
(83, 'Bilinmeyen Bir Kadının Mektubu', 'Stefan Zweig', 'Roman', 'bilinmeyenbirkadininmektubu.jpg', 'Bir kadının, hayatı boyunca tek bir adama duyduğu karşılıksız ve tutkulu aşkı anlatan, kısa fakat çarpıcı bir novella. İnsan ruhunun en hassas ve derin duygularını ustalıkla işler.', '2025-12-04 20:44:49'),
(84, 'Yeraltından Notlar', 'Fyodor Dostoyevski', 'Felsefe', 'yeraltindannotlar.jpg', 'Modern varoluşçu edebiyatın temellerini atan, kimliği belirsiz bir anlatıcının itiraflarını içeren bir eser. Yabancılaşma, öfke ve çaresizlik duygularını derinlemesine inceler.', '2025-12-04 20:44:49'),
(85, 'İnsanın Anlam Arayışı', 'Viktor E. Frankl', 'Psikoloji', 'insaninanamarayisi.jpg', 'Frankl’ın toplama kamplarındaki deneyimlerini ve logoterapi teorisini anlatan, hayatta kalmanın ve anlam bulmanın psikolojik gücünü gösteren ilham verici eser.', '2025-12-04 20:44:49'),
(86, 'Kinyas ve Kayra', 'Hakan Günday', 'Gerilim', 'kinyasvekayra.jpg', 'Yeraltı edebiyatının modern örneklerinden olan bu roman, iki karakterin toplumdan kaçışını, şiddet dolu yolculuklarını ve nihilist sorgulamalarını konu edinir.', '2025-12-04 20:44:49'),
(87, 'Her Temas Bir İz Bırakır', 'Emrah Serbes', 'Roman', 'hertemasbirizbirakir.jpg', 'Şehir hayatının kenar mahallelerinde yaşayan sıradan insanların trajik, komik ve gerçekçi hikayelerini içeren bir öykü kitabıdır. Keskin gözlemleri ve çarpıcı diliyle dikkat çeker.', '2025-12-04 20:44:49'),
(88, 'Şeker Portakalı', 'José Mauro de Vasconcelos', 'Roman', 'sekerportakali.jpg', 'Yoksul bir çocuğun yaşamından kesitler sunan, yürek burkan bir büyüme hikayesi. Çocukluğun masumiyeti, acımasızlığı ve ilk büyük hayal kırıklıklarını anlatır.', '2025-12-04 20:44:49'),
(89, 'Fareler ve İnsanlar', 'John Steinbeck', 'Roman', 'farelerveinsanlar.jpg', 'Büyük Buhran döneminde geçen, iki gezgin işçi George ve Lennie’nin Amerikan rüyasına ulaşma çabasını konu edinen güçlü bir kısa roman. Dostluk ve hayallerin kırılganlığını işler.', '2025-12-04 20:44:49'),
(90, 'İçimizdeki Şeytan', 'Sabahattin Ali', 'Roman', 'icimizdekiseytan.jpg', 'Ömer ve Macide’nin aşk hikayesi üzerinden, bireyin kendi içindeki çatışmaları ve toplumsal baskıları ele alan bir Türk klasiği. Ahlaki ikilemleri sorgular.', '2025-12-04 20:44:49'),
(91, 'Serenad', 'Zülfü Livaneli', 'Roman', 'serenad.jpg', '60 yıl önce Mavi Alay adlı Nazi katliamından kurtulan bir profesörle tanışan Maya’nın dramatik ve tarihi aşk hikayesini anlatır. Tarihi olaylarla evrensel dramları harmanlar.', '2025-12-04 20:44:49'),
(92, 'Genç Werther\'in Acıları', 'Johann Wolfgang Von Goethe', 'Roman', 'gencwertherinacilari.jpg', 'Duygusal ve tutkulu bir genç olan Werther’in, imkansız aşkı Lotte’ye duyduğu yoğun hisleri ve trajik sonunu anlatan mektuplardan oluşan Romantik dönemin en önemli eserlerinden biri.', '2025-12-04 20:44:49'),
(93, 'Yabancı', 'Albert Camus', 'Felsefe', 'yabanci.jpg', 'Meursault adlı karakterin annesinin cenazesindeki kayıtsızlığı ve ardından işlediği cinayet üzerinden, varoluşçuluğun temel kavramlarını ve absürt felsefesini sorgulayan başyapıt.', '2025-12-04 20:44:49'),
(94, 'Sol Ayağım', 'Christy Brown', 'Roman', 'solayagim.jpg', 'Doğuştan serebral palsili olan Christy Brown’ın, sadece sol ayağını kullanarak okumayı, yazmayı ve resim yapmayı öğrenmesini anlatan otobiyografik bir eser. İnsan azminin inanılmaz bir örneği.', '2025-12-04 20:44:49'),
(95, 'Gurur ve Önyargı', 'Jane Austen', 'Roman', 'gururveonyargi.jpg', '19. yüzyıl İngiltere’sinde geçen, Elizabeth ile kibirli Bay Darcy arasındaki ilişkiyi mizahi ve eleştirel bir dille anlatan zamansız bir aşk klasiği.', '2025-12-04 20:44:49'),
(96, 'Tutunamayanlar', 'Oğuz Atay', 'Roman', 'tutunamayanlar.jpg', 'Türk edebiyatının en önemli modern eserlerinden biri olan bu roman, yabancılaşma, aydın sorunları ve modern toplumla çatışmayı araştırır.', '2025-12-04 20:44:49'),
(97, 'Yaşamak', 'Yu Hua', 'Roman', 'yasamak.jpg', 'Çin’in modern tarihini, Xu Fugui adlı bir adamın hayatı üzerinden anlatan dokunaklı bir roman. Değişimler karşısında sıradan bir insanın hayatta kalma mücadelesini gözler önüne serer.', '2025-12-04 20:44:49'),
(98, 'Gün Olur Asra Bedel', 'Cengiz Aytmatov', 'Roman', 'gunolurasrabedel.jpg', 'Manurtlaşma (mankurtluk) mitini modern bir bağlama taşıyan, hafıza, kimlik ve kültürel köklerin önemini sorgulayan güçlü bir eserdir.', '2025-12-04 20:44:49'),
(99, 'Yaprak Dökümü', 'Reşat Nuri Güntekin', 'Roman', 'yaprakdokumu.jpg', 'Geleneksel değerlere bağlı bir ailenin, modernleşme karşısında yaşadığı ahlaki ve ekonomik çöküşü anlatan, Türk edebiyatının unutulmaz eserlerinden biridir.', '2025-12-04 20:44:49'),
(100, 'Mai ve Siyah', 'Halid Ziya Uşaklıgil', 'Roman', 'maivesiyah.jpg', 'Servet-i Fünun dönemi romanı olup, hayalleri ile acı gerçekler arasında sıkışıp kalan genç yazar Ahmet Cemil’in trajedisini anlatır. Erken dönem Türk romancılığının zirvesidir.', '2025-12-04 20:44:49'),
(101, 'Felatun Bey ile Rakım Efendi', 'Ahmet Mithat Efendi', 'Roman', 'felatunbeyverakimefendi.jpg', 'Tanzimat dönemi eseri olup, Batılılaşmayı yanlış anlayan Felatun Bey ile çalışkan Rakım Efendi’yi karşılaştırarak toplumsal bir eleştiri sunar.', '2025-12-04 20:44:49'),
(102, 'Katre-i Matem', 'İskender Pala', 'Roman', 'katreimatem.jpg', 'Lale devri İstanbulu’nda geçen gizemli bir olay örgüsünü ve karmaşık karakter ilişkilerini içeren tarihi bir macera romanıdır.', '2025-12-04 20:44:49'),
(103, 'Bir Ömür Nasıl Yaşanır?', 'İlber Ortaylı', 'Kişisel Gelişim', 'biromurnasilyasanir.jpg', 'Tarihçi İlber Ortaylı\'nın hayatı dolu dolu, verimli ve bilinçli yaşamanın yollarını, tecrübelerini ve tavsiyelerini aktardığı kişisel gelişim ve deneme kitabıdır.', '2025-12-04 20:44:49'),
(104, 'Nutuk', 'Atatürk', 'Tarih', 'nutuk.jpg', 'Mustafa Kemal Atatürk\'ün 1919-1927 yılları arasındaki olayları ve Türkiye Cumhuriyeti\'nin kuruluş sürecini anlattığı, Türk siyasi tarihinin en önemli belgesel kaynağıdır.', '2025-12-04 20:44:49'),
(105, 'Bozkurtlar', 'Nihal Atsiz', 'Fantastik', 'bozkurtlar.jpg', 'Orta Asya Türk destanlarından ilham alan, Türk milliyetçiliği ve kahramanlık temalarını işleyen epik bir romandır.', '2025-12-04 20:44:49'),
(106, 'Da Vinci Şifresi', 'Dan Brown', 'Gerilim', 'davincisifresi.jpg', 'Harvard Üniversitesi simgebilimcisi Robert Langdon’ın, tarihi, sanatsal ve dini sırlarla dolu heyecan verici macerayı anlatan küresel bir best-seller.', '2025-12-04 20:44:49'),
(107, 'Sekizinci Renk', 'Gülten Dayıoğlu', 'Roman', 'sekizincirenk.jpg', 'Çocuk edebiyatının önemli eserlerinden olan bu kitap, küçük bir kızın renkleri farklı görme yeteneği üzerinden, hayal gücü, farklılıklar ve kendini kabul etme temalarını işler.', '2025-12-04 20:57:25'),
(108, 'Huzursuzluğun Kitabı', 'Fernando Pessoa', 'Felsefe', 'huzursuzlugunkitabi.jpg', 'Yazarın kurgusal karakter Bernardo Soares\'in günlüklerinden oluşan bu eser, modern yaşamın getirdiği yabancılaşma, varoluşsal sorgulamalar ve hayata dair derin gözlemleri içerir.', '2025-12-04 20:57:25'),
(109, 'Tehlikeli Oyunlar', 'Oğuz Atay', 'Roman', 'tehlikelioyunlar.jpg', 'Türk postmodern edebiyatının en önemli eserlerinden biri. Hikaye, kendi kurduğu oyunlar dünyasında yaşayan Hikmet Benol\'un modern toplumla çatışmasını ve kimlik arayışını ironik ve derin bir dille anlatır.', '2025-12-04 20:57:25'),
(110, 'Dune', 'Frank Herbert', 'Bilim Kurgu', 'dune.jpg', 'Gelecekteki çöl gezegeni Arrakis\'te geçen, siyaset, din, ekoloji ve insan evrimi üzerine derin felsefi konuları işleyen, bilim kurgu ve fantastik türlerinin başyapıtı.', '2025-12-04 20:57:25'),
(111, 'Bülbülü Öldürmek', 'Harper Lee', 'Roman', 'bulbuluoldurmek.jpg', 'Amerikan edebiyatının önemli eserlerinden. Çocuk karakter Scout\'un gözünden ırksal adaletsizlik, masumiyetin kaybı ve ahlaki cesaret konularını işler.', '2025-12-04 20:57:25'),
(112, 'Homo Sapiens: İnsan Türünün Kısa Bir Tarihi', 'Yuval Noah Harari', 'Tarih', 'homosapiens.jpg', 'İnsan türünün bilişsel devrimden günümüz teknolojik çağına kadar olan evrimini, akıcı ve düşündürücü bir dille anlatan, popüler bilim türünde bir başyapıt.', '2025-12-04 20:57:25'),
(114, 'Ölü Ozanlar Derneği', 'Nancy H. Kleinbaum', 'Roman', 'oluozanlardernegi_1765276946.jpg', 'Hayalleri ve farklılıkları katı okul kurallarıyla sınırlandırılmış bir avuç gencin kendini keşfetme hikâyesine tanıklık etmek ister misiniz? O hâlde Nancy H. Kleinbaum tarafından kaleme alınan Amerikan edebiyatının en başarılı eserlerinden biri olarak gösterilen Ölü Ozanlar Derneği ile tanışmaya hazır olun. Sessiz ve utangaç bir kişiliği olan Todd\'un, ailesi tarafından doktor olması için baskı gören Neil ile oda arkadaşı olmasıyla başlayan macera, zamanın ötesine geçen bir klasiğe dönüşüyor.', '2025-12-09 10:42:26');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kitap_incelemeleri_metin`
--

CREATE TABLE `kitap_incelemeleri_metin` (
  `id` int(11) NOT NULL,
  `kitap_id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `inceleme_metni` text NOT NULL,
  `inceleme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kitap_incelemeleri_metin`
--

INSERT INTO `kitap_incelemeleri_metin` (`id`, `kitap_id`, `kullanici_id`, `inceleme_metni`, `inceleme_tarihi`) VALUES
(5, 109, 2, 'Oğuz Atay\'ın eşsiz baş yapıtı.', '2025-12-06 20:02:55'),
(6, 104, 6, 'Dünya\'nın en iyi ikinci kitabı.', '2025-12-09 09:33:56'),
(7, 112, 8, 'aaa', '2025-12-09 10:40:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kitap_puanlari`
--

CREATE TABLE `kitap_puanlari` (
  `id` int(11) NOT NULL,
  `kitap_id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `puan` int(11) NOT NULL,
  `puanlama_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Tablo döküm verisi `kitap_puanlari`
--

INSERT INTO `kitap_puanlari` (`id`, `kitap_id`, `kullanici_id`, `puan`, `puanlama_tarihi`) VALUES
(1, 57, 2, 5, '2025-12-03 17:09:15'),
(2, 3, 2, 4, '2025-12-03 20:46:57'),
(3, 109, 2, 5, '2025-12-06 20:01:49'),
(4, 108, 2, 5, '2025-12-06 20:03:28'),
(5, 104, 2, 5, '2025-12-06 20:06:31'),
(6, 104, 6, 5, '2025-12-09 09:32:47'),
(7, 112, 8, 5, '2025-12-09 10:40:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profil_resmi` varchar(255) DEFAULT 'default_avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `kullanici_adi`, `eposta`, `sifre`, `kayit_tarihi`, `profil_resmi`) VALUES
(2, 'okqn', 'okanyorux@gmail.com', '$2y$10$9PpeaYteWud.oMqafFGUhOKKYyuR6zvY.GXSbyPFfhgG1fP4wzLlu', '2025-12-01 18:31:34', '2_1764672659.jpg'),
(3, 'crocus', 'zeynep@gmail.com', '$2y$10$FEdxSEoUEyY/vuEnnWNuyO.bbX7Gzgrqg2ElIYm6JrZ9MTTjBRx8u', '2025-12-02 10:33:14', '3_1764671709.jpg'),
(5, 'melih', 'melih@gmail.com', '$2y$10$u0Kx/LkDj11tJOdh4padP.ryTNvP5X7tBUgovnbmMH7yiZ1xsXnEW', '2025-12-02 19:26:36', '5_1764703643.jpg'),
(6, 'ismail', 'ismail@icloud.com', '$2y$10$ce0vFyA7XY0w72W1/eeHouYfU.b98dqM4ugD3Pyfk3t/dRi0qfHbm', '2025-12-09 09:32:20', 'default_avatar.png'),
(8, 'aa', 'a@gmail.com', '$2y$10$FeNLuq5u28K9SrrUF3TqXOnOIZQTzW5z3I3zJWShe03rR8N.oag3e', '2025-12-09 10:39:23', '8_1765276883.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `liste_kitaplari`
--

CREATE TABLE `liste_kitaplari` (
  `id` int(11) NOT NULL,
  `liste_id` int(11) NOT NULL,
  `kitap_id` int(11) NOT NULL,
  `eklenme_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `liste_kitaplari`
--

INSERT INTO `liste_kitaplari` (`id`, `liste_id`, `kitap_id`, `eklenme_tarihi`) VALUES
(5, 2, 24, '2025-12-07 13:49:04'),
(6, 3, 114, '2025-12-09 10:44:23');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `okuma_listeleri`
--

CREATE TABLE `okuma_listeleri` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `liste_adi` varchar(100) NOT NULL,
  `aciklama` text,
  `olusturma_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `okuma_listeleri`
--

INSERT INTO `okuma_listeleri` (`id`, `kullanici_id`, `liste_adi`, `aciklama`, `olusturma_tarihi`) VALUES
(2, 2, 'Okunacaklar', 'Okumak istediklerim.', '2025-12-06 20:10:35'),
(3, 8, 'okumak istediklerim', 'aaa', '2025-12-09 10:44:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `takip`
--

CREATE TABLE `takip` (
  `id` int(11) NOT NULL,
  `takip_eden_id` int(11) NOT NULL,
  `takip_edilen_id` int(11) NOT NULL,
  `takip_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `takip`
--

INSERT INTO `takip` (`id`, `takip_eden_id`, `takip_edilen_id`, `takip_tarihi`) VALUES
(2, 3, 2, '2025-12-02 10:37:33'),
(3, 5, 3, '2025-12-02 19:30:52'),
(4, 5, 2, '2025-12-02 19:31:12'),
(5, 2, 5, '2025-12-02 19:34:29'),
(8, 2, 3, '2025-12-04 13:14:09'),
(9, 2, 6, '2025-12-09 09:36:16'),
(10, 8, 2, '2025-12-09 10:41:43');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

CREATE TABLE `yorumlar` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `alinti_id` int(11) NOT NULL,
  `yorum_metni` text NOT NULL,
  `yorum_tarihi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `yorumlar`
--

INSERT INTO `yorumlar` (`id`, `kullanici_id`, `alinti_id`, `yorum_metni`, `yorum_tarihi`) VALUES
(3, 2, 6, ':)', '2025-12-06 20:13:33'),
(4, 6, 7, 'Abla çok iyi.', '2025-12-09 09:34:56'),
(5, 8, 8, 'aaa', '2025-12-09 10:40:53');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `alintilar`
--
ALTER TABLE `alintilar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`),
  ADD KEY `kitap_id` (`kitap_id`);

--
-- Tablo için indeksler `begeniler`
--
ALTER TABLE `begeniler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_begeni` (`kullanici_id`,`alinti_id`),
  ADD KEY `alinti_id` (`alinti_id`);

--
-- Tablo için indeksler `favoriler`
--
ALTER TABLE `favoriler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_book_unique` (`kullanici_id`,`kitap_id`),
  ADD KEY `kitap_id` (`kitap_id`);

--
-- Tablo için indeksler `kitaplar`
--
ALTER TABLE `kitaplar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kitap_incelemeleri_metin`
--
ALTER TABLE `kitap_incelemeleri_metin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_metin_inceleme` (`kitap_id`,`kullanici_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `kitap_puanlari`
--
ALTER TABLE `kitap_puanlari`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_puan` (`kitap_id`,`kullanici_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanici_adi` (`kullanici_adi`),
  ADD UNIQUE KEY `eposta` (`eposta`);

--
-- Tablo için indeksler `liste_kitaplari`
--
ALTER TABLE `liste_kitaplari`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kitap_liste` (`liste_id`,`kitap_id`),
  ADD KEY `kitap_id` (`kitap_id`);

--
-- Tablo için indeksler `okuma_listeleri`
--
ALTER TABLE `okuma_listeleri`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_liste` (`kullanici_id`,`liste_adi`);

--
-- Tablo için indeksler `takip`
--
ALTER TABLE `takip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `takip_unique` (`takip_eden_id`,`takip_edilen_id`),
  ADD KEY `takip_edilen_id` (`takip_edilen_id`);

--
-- Tablo için indeksler `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`),
  ADD KEY `alinti_id` (`alinti_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `alintilar`
--
ALTER TABLE `alintilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `begeniler`
--
ALTER TABLE `begeniler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `favoriler`
--
ALTER TABLE `favoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `kitaplar`
--
ALTER TABLE `kitaplar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- Tablo için AUTO_INCREMENT değeri `kitap_incelemeleri_metin`
--
ALTER TABLE `kitap_incelemeleri_metin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `kitap_puanlari`
--
ALTER TABLE `kitap_puanlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `liste_kitaplari`
--
ALTER TABLE `liste_kitaplari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `okuma_listeleri`
--
ALTER TABLE `okuma_listeleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `takip`
--
ALTER TABLE `takip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `yorumlar`
--
ALTER TABLE `yorumlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `alintilar`
--
ALTER TABLE `alintilar`
  ADD CONSTRAINT `alintilar_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alintilar_ibfk_2` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `begeniler`
--
ALTER TABLE `begeniler`
  ADD CONSTRAINT `begeniler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `begeniler_ibfk_2` FOREIGN KEY (`alinti_id`) REFERENCES `alintilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `favoriler`
--
ALTER TABLE `favoriler`
  ADD CONSTRAINT `favoriler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoriler_ibfk_2` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `kitap_incelemeleri_metin`
--
ALTER TABLE `kitap_incelemeleri_metin`
  ADD CONSTRAINT `kitap_incelemeleri_metin_ibfk_1` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kitap_incelemeleri_metin_ibfk_2` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `kitap_puanlari`
--
ALTER TABLE `kitap_puanlari`
  ADD CONSTRAINT `kitap_puanlari_ibfk_1` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kitap_puanlari_ibfk_2` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `liste_kitaplari`
--
ALTER TABLE `liste_kitaplari`
  ADD CONSTRAINT `liste_kitaplari_ibfk_1` FOREIGN KEY (`liste_id`) REFERENCES `okuma_listeleri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liste_kitaplari_ibfk_2` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `okuma_listeleri`
--
ALTER TABLE `okuma_listeleri`
  ADD CONSTRAINT `okuma_listeleri_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `takip`
--
ALTER TABLE `takip`
  ADD CONSTRAINT `takip_ibfk_1` FOREIGN KEY (`takip_eden_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `takip_ibfk_2` FOREIGN KEY (`takip_edilen_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `yorumlar`
--
ALTER TABLE `yorumlar`
  ADD CONSTRAINT `yorumlar_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yorumlar_ibfk_2` FOREIGN KEY (`alinti_id`) REFERENCES `alintilar` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
