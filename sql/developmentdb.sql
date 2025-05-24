-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 06, 2025 at 08:00 PM
-- Server version: 11.6.2-MariaDB-ubu2404
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Computer'),
(2, 'Phone'),
(3, 'Sound');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_amount`, `status`) VALUES
(20, 27, '2025-04-06 19:00:07', 1352.00, 'pending'),
(21, 27, '2025-04-06 19:43:35', 234.00, 'pending'),
(22, 27, '2025-04-06 19:43:49', 5311.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(21, 20, 16, 3),
(22, 21, 15, 1),
(23, 22, 19, 14);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `isFeatured` tinyint(1) DEFAULT NULL,
  `isDeleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category_id`, `isFeatured`, `isDeleted`) VALUES
(15, 'Acer 514 CB514-2H-K8SN - Chromebook - 14 inch', 229.00, 'Deze Acer 514 CB514-2H-K8SN Chromebook valt op vanwege zijn lange accuduur en het verlichte toetsenbord. Ook heeft hij een aluminium deksel, wat voor een luxe uitstraling en extra stevigheid zorgt.\n\n\nDeze Chromebook met Basis Snelheid is geschikt voor basistaken zoals webbrowsen, e-mailen of het bekijken van video\'s.\n\nKenmerken van deze Chromebook\nWel in de Chromebook:\n- Een 14 inch Full HD beeldscherm.\n- Een verlicht toetsenbord.\n- Een QWERTY toetsenbordindeling.\n- Een ChromeOS besturingssysteem dat werkt zoals een smartphone, zo werk je met verschillende apps. Het is dus anders dan een Windows Laptop.\n\nNiet in de Chromebook:\n- Geen Touchscreen beeldscherm.\n- Geen 2-in-1 functies.\n- Geen aansluiting voor een 2de beeldscherm.\n\nBelangrijk om te weten\n- Software: Software en apps kun je alleen downloaden uit de Google Play store of Chrome Web Store.\n- Microsoft Office zoals: Word, PowerPoint, Outlook en Excel zijn helaas alleen als online webversie beschikbaar. Een betaald Office 365 pakket is dus geen vereiste.\n- Netwerk: Chromebooks zijn niet altijd geschikt voor op het netwerk van Hogescholen, Universiteiten of andere bedrijven. Neem hiervoor contact op met jouw organisatie.\n- Opslagruimte: Chromebooks hebben vaak zelf niet veel opslagruimte. Dit model heeft 64 GB opslagruimte.\n- Cloud: Je werkt voornamelijk via een WiFi-verbinding vanuit de Google One Cloud. Hierdoor heb je gemakkelijk toegang tot al je bestanden vanuit al je devices.', '550x416.jpg', 1, 1, 0),
(16, 'Lenovo IdeaPad Slim 3 15AMN8 82XQ00JBMH - Laptop - 15.6 inch', 449.00, 'De Lenovo IdeaPad Slim 3 15AMN8 82XQ00JBMH heeft een compact, lichtgewicht ontwerp waardoor hij zeer geschikt om in je rugzak mee te nemen. Met 17,9 mm is hij 10% dunner dan de vorige generatie IdeaPad 3.\n\nDeze laptop is geschikt voor alledaags, licht gebruik zoals het browsen op het internet, e-mail en het typen van verslagen. Ook kan je dankzij het IPS-scherm met goede kijkhoeken en speakers met Dolby Audio lekker genieten van je favoriete films en series.\n\nOnze specialist adviseert deze laptop voor:\n\nDagelijks gebruik: deze laptop is geschikt voor internetten, e-mailen en tekstverwerking.\nStudie: verslagen uitwerken in Word en presentaties maken in Powerpoint (software niet inbegrepen).\nFilms en series kijken in Full HD: het scherm kent een goede beeldkwaliteit voor het bekijken van jouw films en series.\n\nDeze laptop wordt niet aanbevolen voor:\n\nFotobewerking in systemen zoals Photoshop: hiervoor adviseren wij een i7 / Ryzen 7 processor of hoger.\nVideobewerking: deze laptop beschikt niet over een aparte videokaart.\nGaming: deze laptop voldoet niet aan de systeemeisen om moderne games te spelen.\nDe AMD Ryzen 5 processor zorgt ervoor dat jouw alledaagse taken vlot worden uitgevoerd. Deze processor heeft 4 cores.\nDankzij 16 GB werkgeheugen kan deze laptop meerdere taken gelijktijdig uitvoeren. Even wat opzoeken op internet terwijl je een verslag typt in Word en naar muziek luistert is dus geen probleem.\n\nDe SSD zorgt ervoor dat de laptop binnen 15 seconden is opgestart, en met 512 GB opslagruimte kun je makkelijk je programma\'s en bestanden kwijt.\n\nHet 15.6 inch scherm is een mooie balans tussen werkruimte en formaat. Hierdoor is deze IdeaPad Slim 3 prima mee te nemen in je rugzak. Ook het relatief lage gewicht (1,62 kg) helpt hierbij. Het scherm heeft de Full HD resolutie (1920 x 1080 pixels), wat voor algemeen gebruik helemaal prima is.\nEen extra scherm aansluiten kan via de HMDI-poort of USB-C.\n\nHandig is ook het privacyschuifje voor de webcam, en de snellaadfunctie waarmee je na 15 minuten opladen weer 2 uur op de accu kunt werken.\n\nGoed om te weten\nDeze laptop is voorzien van Windows 11 Home.\nDe behuizing is gemaakt van kunststof.\nDeze laptop is niet voorzien van een DVD/CD speler.\nBij deze laptop zit geen Microsoft Office inbegrepen.\nDeze laptop is voorzien van een QWERTY toetsenbord.\nHet toetsenbord van deze laptop heeft geen achtergrondverlichting.', '550x427.jpg', 1, 1, 0),
(17, 'Lenovo IdeaPad Slim 3 16IAH8 83ES0030MH - Laptop - 16 inch', 479.00, 'De Lenovo IdeaPad Slim 3 16IAH8 83ES0030MH heeft een groot scherm met lekker veel werkruimte, maar is toch relatief licht en dun. De 16:10-beeldverhouding zorgt ervoor dat je meer regels tekst of data kunt zien zonder dat je hoeft te scrollen.\n\nDeze laptop is geschikt voor het kijken van films en series, en voor bijv. het maken van presentaties in Powerpoint.\n\nOnze specialist adviseert deze laptop voor:\n- Films en series kijken in Full HD: het scherm kent een hoge beeldkwaliteit voor het bekijken van jouw films en series.\n- Lichte fotobewerking: de processor is hiervoor snel genoeg. Let wel: als je grote projecten in bijv. Photoshop wilt gaan uitvoeren, is een laptop met minimaal een Ryzen 7 / Core i7 processor een betere keuze.\n- Dagelijks gebruik: deze laptop is geschikt voor internetten, e-mailen en tekstverwerking.\n- Studie: verslagen uitwerken in Word en presentaties maken in Powerpoint (software niet inbegrepen).\n\nDeze laptop wordt niet aanbevolen voor:\n- Zware fotobewerking in bijv. Photoshop: hiervoor adviseren we een laptop met minimaal een Ryzen 7 / Core i7 processor.\n- Videobewerking: deze laptop beschikt niet over een aparte videokaart.\n- Gaming: heeft niet de specificaties die nodig zijn voor het spelen van de meest recente games.\n\nDe Intel Core i5 processor (12e generatie) zorgt ervoor dat alledaagse taken snel worden uitgevoerd. De processor heeft 8 cores, en is voorzien van een ingebouwde Intel UHD Graphics videochip. Dankzij de 8 GB RAM is het mogelijk om meerdere lichte programma\'s tegelijk te openen, zoals een Word-document en enkele browser-tabbladen.\n\nVerder heeft deze Lenovo een 512 GB SSD aan boord, wat genoeg ruimte is voor al je programma\'s, foto\'s en andere bestanden. Ook zorgt de SSD ervoor dat de laptop lekker snel opstart.\n\nHet 16 inch scherm heeft goede kijkhoeken dankzij het IPS-paneel, ideaal als je samen met iemand anders een film of serie kijkt. De resolutie van 1920 x 1200 pixels is even scherp als Full HD, maar biedt meer werkruimte in de hoogte dankzij de 16:10-verhouding. Hierdoor passen er meer regels tekst op het scherm dan een standaard 16:9-display.\n\nEen extra monitor sluit je gemakkelijk aan via de HDMI-poort of USB-C.\n\nGoed om te weten\n- Deze laptop is voorzien van Windows 11 Home.\n- De behuizing is gemaakt van kunststof.\n- Bij deze laptop wordt geen Microsoft Office meegeleverd.\n- Deze laptop is niet voorzien van een DVD/CD speler.\n- Het werkgeheugen van deze laptop is niet uitbreidbaar.\n- Deze laptop is voorzien van een QWERTY toetsenbord.', '550x343.jpg', 1, 0, 0),
(18, 'Samsung Galaxy A26 5G - 256GB - Black', 299.00, 'De Samsung Galaxy A26 5G heeft een slank design, fotocamera voor elke situatie en handige AI tools, zoals Circle to Search en Intelligente fotobewerking.\n\nBelangrijkste verschillen met zijn voorganger, de Samsung Galaxy A25:\n\nNieuw design. De Galaxy A26 heeft een groter scherm en is dunner dan de A25, waardoor deze lichter is dan de Galaxy A25.\nEen snellere processor. De Galaxy A26 is sneller dan de Galaxy A25 door de Exynos 1380-chipset.\nIP67 classificering, waardoor de Galaxy A26 (spat)water en stofbestendig is.\nSlank. Glanzend. Stijlvol.\nGeef je stijl een voorsprong met de Galaxy A26 5G. De dunne behuizing ligt comfortabel in je hand, terwijl de glanzende glazen achterkant en de strakke camera-indeling een echte eye-catcher is. Verkrijgbaar in wit, mint en zwart.\n\nMeer plezier op het grote scherm met een dunne rand\nHet 6,7-inch Super AMOLED-scherm met een dunne, geminimaliseerde rand laat je met levendige helderheid genieten van je favoriete entertainment, van de nieuwste video van je favoriete maker tot foto\'s van dierbaren.\n\nGemaakt voor kracht\nGeniet met een gerust hart van je favoriete buitenactiviteiten. De Galaxy A26 5G is IP67-gecertificeerd voor stof- en waterbestendigheid en is klaar om al je hoogtepunten vast te leggen, zelfs in de regen.\n\nCircle to Search\nDe geweldige AI-tools van de Galaxy A Series maken jouw leven makkelijker, leuker én mooier. Benieuwd waar je die sneakers kunt kopen die je op social media of op straat ziet? Zoek alles wat je wilt met Circle to Search: omcirkel het beeld of de tekst en krijg direct de beste resultaten.\n\nIntelligente fotobewerking\nVoor de echte fotografen en social-liefhebbers zijn er meer dan genoeg opties voor intelligente fotobewerking op de Galaxy A Series. Leg de focus echt op jezelf en haal ongewenste mensen, objecten of dieren uit een foto met Object Eraser. Toch nog een beetje extra flair nodig? De aangepaste filters functie doet een AI-analyse welk filter het beste past en laat je dit zelf tweaken met onder meer contrast, kleurwarmte en stijlen.\n\nMaak levensechte foto\'s met de groothoekcamera\nMaak foto\'s van hoge kwaliteit met de 50MP groothoekcamera. Zelfs in beweging kan je stabiele foto\'s maken.\n\nMaak selfies die het delen waard zijn\nMaak selfies die je meteen wilt delen. Leg elk moment vast, van het alledaagse tot het buitengewone ― allemaal met de 13MP Selfie Camera.', '550x717.jpg', 2, 1, 0),
(19, 'Samsung Galaxy A36 5G - 256GB - Awesome Black', 379.00, 'De Samsung Galaxy A36 beschikt over verschillende nieuwe functies. Zo heeft de Galaxy A36 de nieuwste AI-functies zoals Circle to Search en Intelligente fotobewerking. Ook beschikt de Galaxy A36 over een krachtige processor en een groot scherm. Daarnaast is het design van de smartphone vernieuwd.\n\nBelangrijkste verschillen met zijn voorganger, de Samsung Galaxy A35:\n\nNieuwe AI functies, zoals verbeterde Circle to Search en Intelligente fotobewerking\nEen snellere processor door een nieuwe chipset, de Snapdragon 6 Gen3.\nSneller opladen. De oplaadsnelheid is verhoogd van 25 naar 45 watt. De Galaxy A36 laadt daardoor sneller op dan de Galaxy A35.\n\nCircle to Search\nDe geweldige AI-tools van de Galaxy A Series maken jouw leven makkelijker, leuker én mooier. Benieuwd waar je die sneakers kunt kopen die je op social media of op straat ziet? Zoek alles wat je wilt met Circle to Search: omcirkel het beeld of de tekst en krijg direct de beste resultaten.\n\nIntelligente fotobewerking\nVoor de echte fotografen en social-liefhebbers zijn er meer dan genoeg opties voor intelligente fotobewerking op de Galaxy A Series. Leg de focus echt op jezelf en haal ongewenste mensen, objecten of dieren uit een foto met Object Eraser. Toch nog een beetje extra flair nodig? De aangepaste filters functie doet een AI-analyse welk filter het beste past en laat je dit zelf tweaken met onder meer contrast, kleurwarmte en stijlen.\n\nScherpere en meer kleurrijke beelden\nSchiet adembenemende foto’s en video’s dankzij de 50MP-camera met verbluffende 10-bit HDR. Dankzij de vernieuwde 12MP-frontcamera en de nieuwe Video HDR-technologie zijn de selfievideo’s rijker van kleur en tonen scherpere details dan ooit.\n\nGroter en helderder display, smallere schermrand\nGeniet overal van het grote 6,7” FHD+ Super AMOLED-display van de Galaxy A Series. Door de smallere schermranden en verhoogde helderheid tot 1200 nits ga je zo nog meer op in je smartphone. De verbeterde Vision Booster toont zelfs in direct zonlicht ieder beeld op de beste manier door een automatische kleur- en contrastaanpassing.\n\nSterkere processor voor betere prestaties\nDe Galaxy A Series is voorzien van een krachtige processor voor nog meer power. De Galaxy A36 bevat de Snapdragon 6 Gen3 processor: een flinke verbetering ten opzichte van de voorganger. Daarnaast heeft de smartphone een grotere Vapor Chamber, oftewel koelsysteem, zodat je nog langer en soepeler kunt gamen, video’s streamen en multitasken.\n\nKrachtigere batterij\nDankzij de sterke 5000 mAh-batterij kun je extra lang ieder moment van de dag genieten van je Galaxy A Series.\n\nVernieuwd design\nDoor de dunnere behuizing zijn de smartphones stijlvol en elegant, en liggen ze nog beter in de hand. Het uitgesproken design van de camera’s op de achterzijde geven alles een premium uitstraling.', '550x708.jpg', 2, 1, 0),
(20, 'Samsung Galaxy A56 5G - 256GB - Awesome Graphite', 200.00, 'De Samsung Galaxy A56 beschikt over de nieuwste AI tools van Samsung Galaxy. Gebruik Circle to Search om gemakkelijk te vinden wat je zoekt en Intilligente fotobewerking om je foto\'s nóg beter te maken. De Galaxy A56 telefoon beschikt daarnaast over een veelzijdige camera met verbeterde nachtfotografie. Ook heeft de smartphone krachtige hardware met een sterke processor en een groot scherm.\n\nBelangrijkste verschillen met zijn voorganger, de Samsung Galaxy A55:\n\n Een groter scherm, van betere kwaliteit door de dynamsiche verversingssnelheid\nEen snellere processor die 20% sneller werkt. Dit komt door een nieuwe chip, de Exynos 1580 in de Galaxy A56 vs de Exynos 1480 in de A55.\nSneller opladen. De oplaadsnelheid is verhoogd van 25 naar 45 watt. De Galaxy A56 laadt daardoor sneller op dan de Galaxy A55.\n\nCircle to Search\nDe geweldige AI-tools van de Galaxy A Series maken jouw leven makkelijker, leuker én mooier. Benieuwd waar je die sneakers kunt kopen die je op social media of op straat ziet? Zoek alles wat je wilt met Circle to Search: omcirkel het beeld of de tekst en krijg direct de beste resultaten.\n\nIntelligente fotobewerking\nVoor de echte fotografen en social-liefhebbers zijn er meer dan genoeg opties voor intelligente fotobewerking op de Galaxy A Series. Leg de focus echt op jezelf en haal ongewenste mensen, objecten of dieren uit een foto met Object Eraser. Toch nog een beetje extra flair nodig? De aangepaste filters functie doet een AI-analyse welk filter het beste past en laat je dit zelf tweaken met onder meer contrast, kleurwarmte en stijlen.\n\nIedereen goed op de groepsfoto lukt niet altijd. Met de Galaxy A56 bouw je met de AI-gestuurde Beste Gezicht altijd het perfecte totaalplaatje. Je selecteert uit meerdere foto’s de beste resultaten en maakt hiervan één foto waar iedereen op straalt. Ook video’s pas je slim aan met Auto Trim. Door AI haal je moeiteloos de hoogtepunten uit meerdere video’s én combineert dit automatisch tot één topvideo!\n\nScherpere en meer kleurrijke beelden\nSchiet adembenemende foto’s en video’s dankzij de 50MP-camera met verbluffende 10-bit HDR. Dankzij de vernieuwde 12MP-frontcamera en de nieuwe Video HDR-technologie zijn de selfievideo’s rijker van kleur en tonen scherpere details dan ooit.\n\nVerbeterde Nightography\nGa next level met de Galaxy A56 dankzij de verbeterde Nightography. Leg ’s nachts moeiteloos prachtige foto’s en video’s vast, zelfs bij weinig licht, dankzij geavanceerde ruisonderdrukking en 1 μm grote pixels.\n\nAI-portretfotografie\nDankzij AI-portretfotografie sta jij altijd in de spotlights. Smart Auto Focus analyseert het beeld en herkent automatisch achtergronden en onderwerpen voor haarscherpe resultaten. Dankzij AI worden gezichten en andere details op slimme wijze geoptimaliseerd. Ready to shine!\n\nGroter en helderder display, smallere schermrand\nGeniet overal van het grote 6,7” FHD+ Super AMOLED-display van de Galaxy A Series. Door de smallere schermranden en verhoogde helderheid tot 1200 nits ga je zo nog meer op in je smartphone. De verbeterde Vision Booster toont zelfs in direct zonlicht ieder beeld op de beste manier door een automatische kleur- en contrastaanpassing.\n\nSterkere processor voor betere prestaties\nDe Galaxy A Series is voorzien van een krachtige processor voor nog meer power. De Galaxy A56 bevat een Exynos 1580 processor: een flinke verbetering ten opzichte van de voorganger. Daarnaast heeft de smartphone een grotere Vapor Chamber, oftewel koelsysteem, zodat je nog langer en soepeler kunt gamen, video’s streamen en multitasken.\n\nKrachtigere batterij\nDankzij de sterke 5000 mAh-batterij kun je extra lang ieder moment van de dag genieten van je Galaxy A Series.\n\nVernieuwd design\nDoor de dunnere behuizing zijn de smartphones stijlvol en elegant, en liggen ze nog beter in de hand. Het uitgesproken design van de camera’s op de achterzijde geven alles een premium uitstraling.', '550x717.jpg', 2, 1, 0),
(21, 'Draadloze Microfoon Set - 2 Stuks', 55.00, 'Ervaar draadloze vrijheid met de handige Saaf Draadloze Microfoon Set!\nDe Draadloze Microfoon set van Saaf is de perfecte keuze voor heldere geluidsopnames, waar je ook bent. Dankzij het compacte ontwerp en geavanceerde technologieën zoals noise cancelling, kun je makkelijk en snel zonder enige ruis opnemen. Met set van twee microfoons, 30 meter bereik en handige USB-C oplaadcase is de set ideaal voor zowel professioneel als persoonlijk gebruik.\n\nJouw voordelen:\nHelder geluid met noise cancelling:\nLeg kristalheldere audio vast zonder achtergrondgeluiden, zelfs in drukke omgevingen. Perfect voor opnames, presentaties of online meetings.\nPlug-and-play veelzijdigheid:\nGeen gedoe met ingewikkelde instellingen. Sluit de microfoon aan en begin direct met opnemen. Compatibel met Android 5.0 en Apple iOS 10.0.\nDual Microphone Set met 30m bereik:\nGeniet van flexibiliteit en gebruiksgemak met twee microfoons en een draadloos bereik van 30 meter.\nHandige kledingclip en stijlvol ontwerp:\nDe microfoon is lichtgewicht en eenvoudig te bevestigen met de kledingclip. Het slanke en professionele ontwerp maakt hem geschikt voor elke situatie.\nDraagbare USB-C oplaadcase:\nHoud je microfoon opgeladen en beschermd met de meegeleverde USB-C oplaadcase. Neem hem overal mee naartoe voor langdurig gebruik.\nWaarom de Draadloze Microfoon set van Saaf?\nMet de Draadloze Microfoon set kun je rekenen op uitstekende geluidskwaliteit, gebruiksgemak en veelzijdigheid. Deze microfoon is perfect voor iedereen die heldere audio-opnames wil maken, zonder gedoe met kabels of ingewikkelde apparatuur.\n\nGebruiks- en onderhoudsvriendelijk\nDe Draadloze Microfoon set is eenvoudig in gebruik dankzij de plug-and-play functie. Het stevige ontwerp en de draagbare oplaadcase maken onderhoud en transport moeiteloos.\n\nHandige tips!\nLaad de microfoon volledig op voor het eerste gebruik.\nPlaats de microfoon op een optimale positie met de kledingclip voor de beste geluidskwaliteit.\nWat zit er in de verpakking?\n2x Microfoon\n1x Oplaadcase\n1x Oplaadkabel\n1x USB-C naar Lightning\n1x Handleiding\nSpecificaties:\n\nMerk: Saaf\nModel: Wireless Microphone\nKleur: Zwart\nBereik: 30 meter\nNoise cancelling: Ja\nBatterijduur: (hier ontbreekt nog informatie)\nOplaadtijd: (hier ontbreekt nog informatie)\nVerbinding: Bluetooth 5.0\nCompatibiliteit: Android 5.0 en iOS 10.0 en hoger\nServicebelofte:\n\n2 jaar garantie\nKlantenservice: Bereikbaar 5 dagen per week, zowel telefonisch als per mail.\nBestel vandaag nog!\n\nMet de Wireless Microphone van Saaf heb je altijd kraakhelder geluid binnen handbereik. Bestel vandaag nog jouw Saaf Wireless Microphone en geniet van professioneel geluid zonder compromis. Met 100% klanttevredenheidsgarantie en een 30 dagen bedenktijd kun je met een gerust hart bestellen. Wacht niet langer en upgrade jouw audio-opname-ervaring!', '550x682.jpg', 3, 1, 0),
(22, 'Nuvance - Draadloze Microfoon ', 21.00, 'Productbeschrijving\nStap in de toekomst van spraakopnames met de Nuvance draadloze microfoon! Deze betrouwbare plug & play draadloze microfoon biedt kristalheldere geluidskwaliteit en een scala aan handige functies, waaronder een eenvoudig te gebruiken mute-functie. Geschikt om presentaties, interviews, toespraken of gewoon simpelweg audio op te nemen. Ontdek vandaag nog de ongekende mogelijkheden van de Nuvance lavalier microfoon!\n\nDe voordelen van de Nuvance draadloze microfoon:\nDraadloze en storingsvrije audio: Geniet van heldere geluidsopname;\nLavalier of dasspeld microfoon: Discreet en comfortabel te bevestigen;\nCompatibel met smartphones: Geschikt voor diverse apparaten;\nPlug & Play design: Direct klaar voor gebruik zonder installatie;\nInclusief adapter: Geschikt voor smartphones en tablets;\nMute-functie: Handige bediening met één druk op de knop;\nHoge geluidskwaliteit: Ideaal voor professionele spraakopnames.\nMicrofoon draadloos geschikt voor onopvallende bevestiging\nEen van de belangrijkste voordelen van de Nuvance draadloze microfoon is de lavalier of dasspeld microfoon, die zorgt voor een onopvallende bevestiging aan kleding. Dit maakt het bijzonder geschikt voor situaties waarin de spreker zich vrij moet kunnen bewegen, zoals tijdens presentaties of interviews. De microfoon draadloos is discreet en gemakkelijk te bevestigen, waardoor de spreker zich volledig kan concentreren op zijn of haar boodschap.\n\nSimpel plug & play design dasspeld microfoon\nMet het plug & play design van de Nuvance draadloze microfoon is het eenvoudig om in te stellen en te gebruiken. Sluit de USB-ontvanger aan op uw computer of smartphone en u bent klaar om te gaan. Er is geen ingewikkelde installatie nodig en u kunt de microfoon draadloos direct gebruiken. Dit maakt de lavalier micrfofoon een perfecte oplossing voor mensen die snel en gemakkelijk een professionele geluidskwaliteit willen bereiken, zonder tijd te verspillen aan het instellen van ingewikkelde apparatuur.\n\nInhoud van je bestelling:\n\n1x Nuvance draadloze microfoon;\n1x 8-pin converter;\n1x Nederlands- en Engelstalige handleiding.\nVerbeter uw audio opnames met de Nuvance draadloze microfoon.', '550x771.jpg', 3, 1, 0),
(23, 'Nuvance - Draadloze Microfoon - 2 Stuks', 12.00, 'Ontketen je creativiteit met de Nuvance draadloze microfoon set! Geniet van kristalhelder geluid en volledige bewegingsvrijheid, waar je ook bent. Deze gebruiksvriendelijke en veelzijdige draadloze microfoon set is jouw ultieme partner voor professionele opnames en indrukwekkende presentaties.\n\nVoordelen van onze Nuvance microfoon draadloos set:\nVolledige bewegingsvrijheid: geen kabels voor ultiem gemak.\n3-in-1 ontvanger: voor maximale flexibiliteit met verschillende apparaten.\nKristalhelder geluid: dankzij hoogwaardige geluidskwaliteit.\nPlug & Play: direct te gebruiken zonder extra software.\nLange batterijduur: ideaal voor langdurige opnames.\nDiscreet lavalier ontwerp: eenvoudig en onopvallend te bevestigen.\nDasspeld microfoons met 3 in 1 adapter\nDe Nuvance draadloze microfoon set is uitgerust met een 3-in-1 ontvanger, compatibel met USB-C, 8-pin en 3.5mm jack aansluitingen. Deze veelzijdige ontvanger maakt het eenvoudig om te schakelen tussen verschillende apparaten, zoals smartphones, laptops tablets en camera\'s. Of je nu onderweg bent of in een studio opneemt, deze ontvanger biedt je de ultieme vrijheid en gemak voor al je opnamebehoeften.\n\nPlug & play dasspeld microfoons\nMet de Nuvance dasspeld microfoon set hoef je geen tijd te verspillen aan ingewikkelde instellingen of het installeren van extra software. Sluit de microfoon eenvoudig aan op je apparaat en je bent direct klaar om op te nemen. Deze gebruiksvriendelijke functie zorgt voor een naadloze opname-ervaring, waardoor het ideaal is voor zowel beginners als professionals die snel en efficiënt willen werken.\n\nDiscreet draadloze microfoon ontwerp\nHet discrete lavalier ontwerp zorgt ervoor dat de microfoon eenvoudig en onopvallend te bevestigen is aan kleding, zonder afbreuk te doen aan je uitstraling. Dankzij het compacte formaat blijft de microfoon subtiel verborgen, terwijl je toch profiteert van professionele geluidskwaliteit. Ideaal voor presentaties, interviews en video\'s waarbij een onzichtbare en handsfree oplossing gewenst is.\n\nInhoud van je bestelling:\n\n2x Nuvance microfoon draadloos;\n1x 3 in 1 ontvanger;\n1x USB-C oplaadkabel;\n1x Nederlands- en Engelstalige handleiding.\nVerhoog je opnamekwaliteit met de Nuvance microfoon draadloos set en ervaar ultieme vrijheid en gebruiksgemak!\n\n', '550x788.jpg', 3, 1, 0),
(24, 'Strolox® Draadloze Microfoon - 2 Stuks', 29.99, 'Stap in de wereld van ultieme geluidsopnames met de Strolox Microfoon, jouw onmisbare vriend voor het vastleggen van kristalheldere audio, waar je ook bent. Deze krachtige lavalier-microfoon, met zijn twee gevoelige capsules, staat garant voor een opname-ervaring die de grenzen van geluidskwaliteit verlegt, of je nu onderweg bent, in een drukke omgeving bent, of in het comfort van je eigen ruimte.\n\nWaarom Strolox?\n\nVerbluffende Geluidskwaliteit: Ervaar kristalheldere opnames met een diepte en helderheid die je nooit voor mogelijk had gehouden, dankzij de gevoelige microfoons van de Strolox.\n\nDraadloze Vrijheid: Geniet van de vrijheid om te bewegen zonder beperkingen, met een draadloos bereik tot 100 meter, waardoor je volledige flexibiliteit hebt tijdens opnamesessies.\n\nDubbele Microfoons: Met twee geïntegreerde microfoons leg je niet alleen je stem vast, maar ook de subtiliteiten van geluiden om je heen, voor een meeslepende luisterervaring.\n\nSnelle Oplaadfunctie: Dankzij het snellaadsysteem van de Strolox Microfoon ben je altijd klaar om op te nemen, met minimale wachttijd en maximale opnametijd.\n\nGeavanceerde Ruisonderdrukking: Elimineer storende achtergrondgeluiden en focus op wat echt belangrijk is, dankzij de ingebouwde ruisonderdrukkingsfunctie die jouw opnames helder en professioneel maakt.\n\nEenvoudig in Gebruik: Met een intuïtief ontwerp en gebruiksvriendelijke functies biedt de Strolox Microfoon een moeiteloze opname-ervaring, zodat jij je kunt concentreren op je boodschap, niet op de technologie.\n\nDubbel Plezier met Dubbele Microfoons: Met niet één, maar twee zorgvuldig afgestemde microfoons biedt de Strolox Microfoon een dynamische en rijke geluidsweergave. Elk detail van je stem en omgevingsgeluid wordt nauwkeurig vastgelegd, waardoor je luisteraars zich volledig ondergedompeld voelen in jouw verhaal.\n\nNaadloze Connectiviteit, Grenzeloze Vrijheid: Dankzij de geavanceerde draadloze technologie van de Strolox Microfoon geniet je van ultieme vrijheid tijdens het opnemen. Met een bereik tot wel 100 meter blijf je verbonden met je opnameapparaat, zelfs als je je door verschillende ruimtes beweegt, zonder concessies te doen aan de geluidskwaliteit.\n\nSlimme Functionaliteit voor Optimaal Gebruiksgemak: Ontworpen met het oog op gebruiksvriendelijkheid, biedt de Strolox Microfoon een scala aan handige functies die je opname-ervaring verbeteren. Van het snelle oplaadsysteem dat je binnen no-time weer klaarstoomt voor opnames, tot de praktische kledingclip die ervoor zorgt dat de microfoon altijd binnen handbereik is, elke functie is ontworpen om jouw opnameproces te optimaliseren.\n\nLevendige Opnames in Elke Omgeving: Dankzij de geavanceerde ruisonderdrukkingstechnologie van de Strolox Microfoon blijft jouw stem helder en krachtig, zelfs in de meest uitdagende omstandigheden. Laat achtergrondgeluiden en storende ruis geen spelbreker zijn voor jouw opnamesessies, of je nu binnen bent, in een lawaaiige omgeving, of buiten opneemt.\n\nOntdek de Vrijheid van Creatieve Expressie: Of je nu een presentatie geeft, een interview afneemt, of een podcast opneemt, met de Strolox Microfoon kun je moeiteloos jouw stem laten horen, waar en wanneer je maar wilt. Deze veelzijdige microfoon opent de deur naar een wereld van creatieve mogelijkheden, waardoor je je verhaal op jouw eigen unieke manier kunt vertellen.\n\nBetrouwbaarheid en Kwaliteit Gegarandeerd: Bestel vandaag nog jouw Strolox Microfoon met het vertrouwen dat je investeert in betrouwbaarheid en kwaliteit. We zijn ervan overtuigd dat je onder de indruk zult zijn van de prestaties en veelzijdigheid van onze microfoon. Maar mocht je toch niet helemaal tevreden zijn, dan kun je hem binnen 30 dagen retourneren voor een volledige terugbetaling.\n\nMaak je klaar om jouw geluid te laten horen met de Strolox Microfoon - jouw ultieme partner voor het vastleggen van levensechte audio. Bestel nu en laat jouw stem schitteren als nooit tevoren!', '550x528.jpg', 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(0, 'Customer'),
(1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `hashed_password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `hashed_password`, `email`, `full_name`, `role_id`, `phone`, `refresh_token`) VALUES
(22, 'admin', '$2y$12$RTAVk6m0dwZF4JyMcZmibusOjFGXK.uVpFrC9gGXcTX6u1fwiHzh.', 'admin@admin.com', 'Admin User', 1, '0613371928', '$2y$12$hd2nzj1QMj0o/mYWTvo1cuz1mVdJX5h2Wea8/kapvie7Hcc9SszyK'),
(27, 'user', '$2y$12$nINkHbt2ue/3P2cAW7oX6eYSSsEhYn9kR4xA7EFuRuby/AQ3RvA/2', 'user@user.com', 'User User', 0, '0613333333', '$2y$12$8Pqg6XtbBDEaAV9smLUri.dMTecaOD0d2okqA3tPFGf9x0VPTOkPe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
