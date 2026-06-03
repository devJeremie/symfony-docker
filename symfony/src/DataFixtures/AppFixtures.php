<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Destination;
use App\Entity\Testimonial;
use App\Entity\Travel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // ── DESTINATIONS ──────────────────────────────────────────────────
        $destinations = [];

        $destData = [
            ['Bali',      'Indonésie',  'DPS', 'Île des dieux balinaise, Bali conjugue rizières en terrasses, temples hindous millénaires et plages de sable volcanique. Un sanctuaire de spiritualité et de beauté naturelle au cœur de l\'archipel indonésien.'],
            ['Marrakech', 'Maroc',      'RAK', 'Ville impériale aux mille couleurs, Marrakech envoûte avec ses souks labyrinthiques, ses palais ornés de zelliges et l\'effervescence de la place Jemaa el-Fna. L\'orient à son apogée.'],
            ['Tokyo',     'Japon',      'NRT', 'Mégapole fascinante où tradition et ultra-modernité coexistent en harmonie, Tokyo offre des temples bouddhistes enserrés entre gratte-ciels, une gastronomie sublime et une énergie incomparable.'],
            ['Paris',     'France',     'CDG', 'Capitale mondiale de l\'art, de la mode et de la gastronomie, Paris séduit par ses monuments iconiques, ses musées inégalés et l\'art de vivre à la française. La Ville Lumière dans toute sa splendeur.'],
            ['New York',  'États-Unis', 'JFK', 'La ville qui ne dort jamais, New York fascine par son skyline légendaire, la diversité de ses quartiers et l\'énergie électrisante de ses rues. Un concentré de modernité et de culture mondiale.'],
            ['Reykjavik', 'Islande',    'KEF', 'Aux confins du cercle arctique, l\'Islande révèle ses merveilles géologiques : aurores boréales, geysers fumants, glaciers millénaires et volcans actifs. Une nature brute et sauvage à couper le souffle.'],
            ['Cusco',     'Pérou',      'CUZ', 'Ancienne capitale de l\'Empire inca, Cusco est le point de départ vers le Machu Picchu et la mystérieuse civilisation andine. L\'Amazonie, les Andes et des paysages grandioses vous y attendent.'],
            ['Nairobi',   'Kenya',      'NBO', 'Porte d\'entrée des grandes savanes africaines, Nairobi permet d\'observer les Big Five dans leurs habitats naturels. Safaris au Masai Mara, rencontres avec les Massaïs et couchers de soleil inoubliables.'],
            ['Sydney',    'Australie',  'SYD', 'Ville cosmopolite aux plages légendaires, Sydney combine l\'Opéra House mondialement célèbre, le Harbour Bridge, les plages de Bondi et les espaces naturels de la Blue Mountains.'],
        ];

        foreach ($destData as [$name, $country, $iata, $desc]) {
            $d = new Destination();
            $d->setName($name)->setCountry($country)->setIataCode($iata)->setDescription($desc);
            $manager->persist($d);
            $destinations[$name] = $d;
        }

        // ── TRAVELS ───────────────────────────────────────────────────────
        $travels = [];

        $travelData = [
            // Bali
            ['Bali', 'Retraite spirituelle à Bali', 'bien-etre', 'published', '2026-09-12', '2026-09-26', '2490.00', 12,
                'Plongez au cœur de la spiritualité balinaise avec des séances de yoga au lever du soleil dans les rizières d\'Ubud, des ateliers de méditation guidés par des maîtres locaux et des rituels de purification au Temple des Eaux. Chaque journée débute par l\'offrande aux dieux et se clôt par un coucher de soleil depuis le temple de Tanah Lot.'],
            ['Bali', 'Surf & Nature à Bali', 'aventure', 'published', '2026-10-05', '2026-10-17', '1990.00', 10,
                'De Kuta à Uluwatu en passant par Canggu, partez à la découverte des meilleures vagues de l\'île avec nos instructeurs certifiés. Entre deux sessions de surf, explorez les falaises spectaculaires du sud de Bali, snorkeling à Nusa Penida et randonnées dans la forêt des singes.'],
            // Marrakech
            ['Marrakech', 'Magie de Marrakech', 'culture', 'published', '2026-11-08', '2026-11-16', '1350.00', 14,
                'Perdez-vous dans les ruelles colorées de la médina, négociez dans les souks d\'épices et d\'artisanat, et savourez un tajine au bord des fontaines de la place Jemaa el-Fna. Notre guide expert vous dévoilera les palais cachés et les jardins secrets que les touristes ne voient jamais.'],
            ['Marrakech', 'Désert & Dunes du Sahara', 'aventure', 'published', '2027-02-14', '2027-02-22', '1780.00', 10,
                'Au départ de Marrakech, traversez les gorges du Dadès et rejoignez les immenses dunes d\'Erg Chebbi à Merzouga. Bivouac sous les étoiles dans un camp berbère de luxe, balade au coucher du soleil à dos de dromadaire et lever de soleil sur les dunes d\'or.'],
            // Tokyo
            ['Tokyo', 'Découverte du Japon', 'culture', 'published', '2026-10-20', '2026-11-03', '3290.00', 12,
                'Tokyo, Kyoto, Nara, Hiroshima et Miyajima : parcourez le Japon des temples Shinto et des geishas jusqu\'aux robots restaurants d\'Akihabara. La cérémonie du thé, le Fushimi Inari et les sushis du marché Tsukiji offrent une immersion totale dans la culture nippone.'],
            ['Tokyo', 'Japon en fleurs de cerisier', 'nature', 'draft', '2027-03-28', '2027-04-10', '3590.00', 10,
                'Le hanami, contemplation des cerisiers en fleurs, est l\'un des rituels les plus poétiques du Japon. Admirez les sakuras dans les parcs d\'Ueno et de Shinjuku Gyoen à Tokyo, puis au château de Hirosaki et au mont Yoshino.'],
            // Paris
            ['Paris', 'Week-end à Paris', 'culture', 'published', '2026-09-25', '2026-09-28', '890.00', 16,
                'Trois jours en immersion dans la capitale française : visite privée du Louvre avant l\'ouverture au public, promenade en bateau-mouche sur la Seine au coucher du soleil, dîner gastronomique dans un bistrot du Marais et ascension de la Tour Eiffel.'],
            ['Paris', 'Paris Gastronomique & Marchés', 'culture', 'published', '2026-11-20', '2026-11-24', '1190.00', 10,
                'Visite des Halles de Rungis tôt le matin, cours de cuisine dans un atelier du 6e arrondissement, dégustation de fromages chez des affineurs d\'exception et tournée des boulangeries primées. Du marché d\'Aligre aux caves à vins de Saint-Germain.'],
            // New York
            ['New York', 'New York City', 'culture', 'published', '2026-12-26', '2027-01-04', '2890.00', 14,
                'Manhattan, Brooklyn, le Bronx et Queens : découvrez les quatre visages de New York avec notre guide local. Empire State Building, Central Park, High Line, MoMA, Brooklyn Bridge et Statue de la Liberté. Soirée Broadway et brunch dans un restaurant celebrity.'],
            ['New York', 'Road Trip côte Est USA', 'culture', 'draft', '2027-06-15', '2027-06-27', '3190.00', 14,
                'New York, Washington D.C., Boston et les chutes du Niagara en un circuit exceptionnel. Visitez la Maison Blanche, parcourez le Freedom Trail à Boston et assistez au spectacle grandiose des chutes du Niagara côté canadien.'],
            // Islande
            ['Reykjavik', 'Aurores Boréales en Islande', 'nature', 'published', '2027-01-16', '2027-01-24', '2890.00', 10,
                'Partez chasser les aurores boréales dans les paysages volcaniques de l\'Islande hivernale. Cercle d\'Or, Geysir, chutes de Gullfoss et Jökulsárlón avec ses icebergs bleutés. Logement dans des cabines de verre pour contempler les aurores depuis votre lit.'],
            ['Reykjavik', 'Trekking & Volcans d\'Islande', 'aventure', 'published', '2026-08-01', '2026-08-11', '2490.00', 8,
                'Randonnée dans le parc national de Þórsmörk, descente dans le cratère du volcan Thrihnukagigur, traversée des hautes terres du Landmannalaugar avec ses montagnes de rhyolite multicolores. Bains dans les sources chaudes naturelles après chaque étape.'],
            // Pérou
            ['Cusco', 'Machu Picchu & Incas', 'aventure', 'published', '2026-09-06', '2026-09-21', '2990.00', 10,
                'Suivez le chemin des Incas jusqu\'à la cité perdue du Machu Picchu, classée au patrimoine mondial de l\'UNESCO. Lac Titicaca, Salines de Maras, ruines de Pisac et cérémonie chamanique avec un curandero local au programme.'],
            ['Cusco', 'Amazonie Péruvienne', 'nature', 'draft', '2027-07-04', '2027-07-14', '2750.00', 8,
                'Pénétrez au cœur de la forêt amazonienne depuis Puerto Maldonado. Observation de la faune sauvage (jaguars, tapirs, singes), pêche aux piranhas, randonnée nocturne et rencontre avec les communautés indigènes Ese\'eja.'],
            // Kenya
            ['Nairobi', 'Safari Grande Migration', 'nature', 'published', '2026-08-22', '2026-09-04', '4290.00', 8,
                'Assistez au plus grand spectacle naturel de la planète : la Grande Migration des gnous et zèbres dans le Masai Mara. Nuits en camp de luxe sous les étoiles, sorties safari à l\'aube avec des guides Massaïs et survol en montgolfière au lever du soleil.'],
            ['Nairobi', 'Kenya & Zanzibar', 'aventure', 'published', '2026-12-06', '2026-12-20', '4890.00', 10,
                'Combinez le safari au Masai Mara avec la détente sur les plages de corail de Zanzibar. Une semaine dans la savane à observer éléphants et lions, puis une semaine sur l\'île aux épices entre plongée, plages de sable blanc et Stone Town classée UNESCO.'],
            // Australie
            ['Sydney', 'Grand Tour d\'Australie', 'aventure', 'published', '2027-01-08', '2027-01-29', '5290.00', 12,
                'Sydney, Melbourne, Uluru et la Grande Barrière de Corail : le meilleur de l\'Australie en 3 semaines. Opéra House et Bondi Beach, quartiers branchés de Melbourne, le rocher sacré Uluru au coucher du soleil et plongée dans les eaux turquoise du Queensland.'],
            ['Sydney', 'Outback & Côte Sauvage', 'aventure', 'draft', '2027-04-18', '2027-05-04', '4590.00', 8,
                'Partez à la conquête de l\'Outback australien : Red Centre, Kings Canyon, Kata Tjuta et la piste de Gibb River Road au cœur du Kimberley. Nuits à la belle étoile dans le désert et rencontres avec les communautés Aborigènes.'],
        ];

        foreach ($travelData as [$dest, $title, $cat, $status, $dep, $ret, $price, $max, $desc]) {
            $travel = new Travel();
            $travel->setTitle($title)
                ->setDescription($desc)
                ->setDepartureDate(new \DateTime($dep))
                ->setReturnDate(new \DateTime($ret))
                ->setPrice($price)
                ->setMaxParticipants($max)
                ->setStatus($status)
                ->setCategory($cat)
                ->setDestination($destinations[$dest]);
            $manager->persist($travel);
            $travels[$title] = $travel;
        }

        // ── BOOKINGS ──────────────────────────────────────────────────────
        $bookingData = [
            ['Retraite spirituelle à Bali',   'Sophie',   'Marchand',  'sophie.marchand@email.fr',  '0612345678', 2, 'confirmed'],
            ['Retraite spirituelle à Bali',   'Julien',   'Moreau',    'julien.moreau@email.fr',    '0623456789', 1, 'confirmed'],
            ['Surf & Nature à Bali',          'Léa',      'Dupont',    'lea.dupont@email.fr',       '0634567890', 2, 'confirmed'],
            ['Magie de Marrakech',            'Antoine',  'Bernard',   'antoine.bernard@email.fr',  '0645678901', 3, 'confirmed'],
            ['Magie de Marrakech',            'Camille',  'Lefebvre',  'camille.lefebvre@email.fr', '0656789012', 2, 'pending'],
            ['Découverte du Japon',           'Thomas',   'Leroy',     'thomas.leroy@email.fr',     '0667890123', 2, 'confirmed'],
            ['Découverte du Japon',           'Marie',    'Simon',     'marie.simon@email.fr',      '0678901234', 1, 'confirmed'],
            ['Week-end à Paris',              'Pierre',   'Laurent',   'pierre.laurent@email.fr',   '0689012345', 4, 'confirmed'],
            ['New York City',                 'Isabelle', 'Petit',     'isabelle.petit@email.fr',   '0690123456', 2, 'confirmed'],
            ['New York City',                 'Nicolas',  'Garcia',    'nicolas.garcia@email.fr',   '0601234567', 3, 'pending'],
            ['Aurores Boréales en Islande',   'Emma',     'Roux',      'emma.roux@email.fr',        '0611234567', 2, 'confirmed'],
            ["Trekking & Volcans d'Islande",  'Lucas',    'Fournier',  'lucas.fournier@email.fr',   '0622345678', 1, 'confirmed'],
            ['Machu Picchu & Incas',          'Chloé',    'Girard',    'chloe.girard@email.fr',     '0633456789', 2, 'confirmed'],
            ['Safari Grande Migration',       'Maxime',   'Bonnet',    'maxime.bonnet@email.fr',    '0644567890', 2, 'confirmed'],
            ['Kenya & Zanzibar',              'Laura',    'Dubois',    'laura.dubois@email.fr',     '0655678901', 2, 'pending'],
            ["Grand Tour d'Australie",        'Romain',   'Martin',    'romain.martin@email.fr',   '0666789012', 2, 'confirmed'],
        ];

        foreach ($bookingData as [$travelTitle, $fn, $ln, $email, $phone, $persons, $status]) {
            $booking = new Booking();
            $booking->setFirstName($fn)
                ->setLastName($ln)
                ->setEmail($email)
                ->setPhone($phone)
                ->setNumberOfPersons($persons)
                ->setStatus($status)
                ->setTravel($travels[$travelTitle]);
            $booking->calculateTotalPrice();
            $manager->persist($booking);
        }

        // ── TESTIMONIALS ──────────────────────────────────────────────────
        $testimonialData = [
            ['Sophie M.',   'Bali',      5, 'Le voyage à Bali a dépassé toutes mes attentes. La retraite de yoga à Ubud était transformatrice, et le guide local nous a emmené dans des temples que seuls les initiés connaissent. Une expérience spirituelle profonde.', '-45 days'],
            ['Julien R.',   'Marrakech', 5, 'Marrakech m\'a envoûté dès les premières heures. Le riad dans lequel nous logions était une oasis de sérénité au cœur de l\'agitation des souks. La guide nous a fait découvrir une ville que les touristes ne voient jamais.', '-32 days'],
            ['Camille D.',  'Tokyo',     5, 'Le Japon, c\'est une autre planète. TravelDock nous a proposé un itinéraire parfaitement équilibré entre culture traditionnelle et modernité tokyoïte. La cérémonie du thé à Kyoto et le marché Tsukiji resteront des souvenirs impérissables.', '-18 days'],
            ['Antoine B.',  'Islande',   5, 'Voir les aurores boréales depuis notre cabine de verre en Islande... impossible de trouver les mots. L\'organisation était parfaite, les guides passionnés et les paysages à couper le souffle.', '-60 days'],
            ['Marie L.',    'Kenya',     5, 'Le safari au Masai Mara pendant la Grande Migration est tout simplement le plus beau spectacle naturel que j\'aie jamais vu. TravelDock nous a placés aux premières loges. Je rêve d\'y retourner.', '-90 days'],
            ['Thomas G.',   'Pérou',     5, 'Le Chemin des Incas jusqu\'au Machu Picchu est une expérience hors du commun. Notre guide quechua nous a raconté l\'histoire de son peuple avec une passion communicative. Un voyage qui change une vie.', '-120 days'],
            ['Emma P.',     'Australie', 4, 'Le Grand Tour d\'Australie était fantastique. J\'aurais aimé passer plus de temps à Uluru, ce lieu sacré dégage quelque chose d\'indicible. La Grande Barrière de Corail était splendide.', '-75 days'],
            ['Nicolas F.',  'New York',  5, 'New York à Noël, c\'est un rêve devenu réalité. Les décorations des grands magasins, le marché de Bryant Park, le concert à Carnegie Hall... TravelDock avait pensé à tout.', '-10 days'],
            ['Laura S.',    'Bali',      5, 'Le cours de cuisine balinaise chez l\'habitant, la visite du marché d\'Ubud au lever du soleil et les cérémonies de purification dans les temples font de ce voyage une initiation culturelle intense.', '-55 days'],
            ['Pierre M.',   'Marrakech', 4, 'Marrakech est une ville qui se mérite. Elle révèle sa beauté à qui prend le temps de s\'y perdre. Notre guide était exceptionnel. Le seul bémol : j\'aurais voulu rester deux semaines de plus.', '-40 days'],
            ['Isabelle C.', 'Tokyo',     5, 'Voyage au Japon pendant la saison des cerisiers : un rêve absolu. Les parcs d\'Ueno et de Shinjuku transformés en mer de fleurs roses... Inoubliable.', '-25 days'],
            ['Romain V.',   'Pérou',     5, 'La traversée du lac Titicaca, le marché de Pisac et l\'arrivée au Machu Picchu dans la brume du matin sont des instants qui appartiennent à une autre dimension.', '-150 days'],
        ];

        foreach ($testimonialData as [$author, $dest, $rating, $content, $daysAgo]) {
            $t = new Testimonial();
            $t->setAuthorName($author)
                ->setDestination($dest)
                ->setRating($rating)
                ->setContent($content)
                ->setCreatedAt(new \DateTime($daysAgo));
            $manager->persist($t);
        }

        $manager->flush();
    }
}
