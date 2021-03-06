<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class WordCardsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		WordCard::create([
			'word' => 'Такела́ж',
			'answer' => 'общее название всех снастей на судне или вооружение отдельной мачты или рангоутного дерева, употребляемое для крепления рангоута и управления им и парусами.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Фили́стер',
			'answer' => 'человек без духовных потребностей, тот, кто не ценит искусство, не разделяет связанных с ним эстетических или духовных ценностей.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Ло́феры',
			'answer' => 'модель туфель без шнурков, по форме напоминающих мокасины, только с подошвой и на низком широком каблуке,могут быть снабжены декоративными кисточками.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Инса́йт',
			'answer' => 'интеллектуальное явление, суть которого в неожиданном понимании стоящей проблемы и нахождении её решения.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Акрофобия',
			'answer' => 'неконтролируемый иррациональный страх высоты.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Эфебифобия',
			'answer' => 'психологическая и социальная боязнь молодежи.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Ксенофобия',
			'answer' => 'страх или неуважение по отношению к иностранцам или незнакомым людям.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Акрибофо́бия',
			'answer' => 'навязчивый страх не понять смысл прочитанного.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Персо́на нон гра́та',
			'answer' => 'дипломатический термин, который используют для обозначения лица, чье назначение в качестве дипломатического представителя в какую-либо страну не одобрено этой страной.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Ке́йтеринг',
			'answer' => 'оорганизация ресторанного обслуживания на выезде; доставка блюд на мероприятие, в офис, в загородный дом и т.д',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Каптча',
			'answer' => 'каптча или капча – это специальный механизм, с помощью которого сайт отличает людей от компьютеров (спам-роботов, ботов и т.д.).',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Марципа́н',
			'answer' => 'это приготовленная из тертого миндаля и сахарной пудры/сахарного сиропа густая смесь, похожая на тесто.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'ЮНЕСКО',
			'answer' => 'крупная международная организация, которая занимается вопросами образования, науки и культуры во всем мире, призвана поддерживать всеобщее уважение прав и свобод человека, согласно Уставу ООН.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Пра́ймериз',
			'answer' => 'предварительные выборы, предвыборы кандидата от одной партии.',
			'category_id' => '1'
		]);

		WordCard::create([
			'word' => 'Ли́зинг',
			'answer' => 'комплекс имущественных и экономических отношений, возникающих в связи с приобретением в собственность имущества и последующей сдачей его во временное пользование за определенную плату.',
			'category_id' => '1'
		]);

	}

}