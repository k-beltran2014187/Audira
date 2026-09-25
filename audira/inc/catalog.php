<?php
/**
 * Site content in one place: products, ASINs, comparison table and FAQ.
 *
 * HOW TO ADD YOUR PRODUCTS
 * The easy way: Settings → Audira Affiliate → Products. Values saved there
 * override the defaults below. 'url' accepts any Amazon link, including
 * SiteStripe short links (https://amzn.to/…).
 *
 * Amazon's Operating Agreement does not allow hard-coded prices or star
 * ratings copied from Amazon, so buttons say "Check price on Amazon".
 * The "Audira score" is your own editorial rating — keep it honest.
 *
 * @package Audira
 */

defined( 'ABSPATH' ) || exit;

/**
 * Editor's picks (the main money section).
 */
function audira_catalog_picks() {
	return audira_apply_saved_products( 'picks', audira_catalog_picks_defaults() );
}

function audira_catalog_picks_defaults() {
	return array(
		array(
			'asin'     => 'B0F99S235X',
			'url'      => 'https://amzn.to/4rGlJ1p',
			'badge'    => 'Best overall',
			'title'    => 'ELEHEAR Beyond Pro OTC Hearing Aids',
			'image'    => 'pick-rechargeable.svg',
			'score'    => '9.5',
			'best_for' => 'Most people with mild to moderate hearing loss',
			'bullets'  => array(
				'VocClear 2.0 processing for noticeably clearer speech',
				'About 20 hours per charge — a 15-minute top-up adds hours more',
				'Bluetooth 5.3 streaming and real-time translation in the app',
			),
			'featured' => true,
		),
		array(
			'asin'     => 'B0FMSCYRV3',
			'url'      => 'https://amzn.to/4j84hAT',
			'badge'    => 'Best premium',
			'title'    => 'Jabra Enhance Select 700',
			'image'    => 'pick-ric-silver.svg',
			'score'    => '9.3',
			'best_for' => 'Clinic-level care without visiting a clinic',
			'bullets'  => array(
				'Licensed audiology support included',
				'Bluetooth LE Audio streaming for iPhone and Android',
				'Nearly invisible receiver-in-canal design',
			),
			'featured' => false,
		),
		array(
			'asin'     => 'B0DGXQWPC9',
			'url'      => 'https://amzn.to/3ToY1tW',
			'badge'    => 'Best value',
			'title'    => 'ELEHEAR Beyond OTC Hearing Aids',
			'image'    => 'pick-ric-pair.svg',
			'score'    => '9.0',
			'best_for' => 'Great sound on a tighter budget',
			'bullets'  => array(
				'AI speech enhancement plus tinnitus masking',
				'Up to 20 hours per charge, about 100 with the case',
				'Turns on and off automatically in the case',
			),
			'featured' => false,
		),
		array(
			'asin'     => 'B0GS1VFLFQ',
			'url'      => 'https://amzn.to/4heXm7z',
			'badge'    => 'Longest battery',
			'title'    => 'Yeasound RIC800 Hearing Aids',
			'image'    => 'pick-ric-graphite.svg',
			'score'    => '8.9',
			'best_for' => 'Long days away from a charger',
			'bullets'  => array(
				'Up to 124 hours of total battery with the case',
				'IPX8 water resistance',
				'AI noise reduction with Bluetooth calls and music',
			),
			'featured' => false,
		),
		array(
			'asin'     => 'B0H5TZGP3M',
			'url'      => 'https://amzn.to/4Azqn5c',
			'badge'    => 'Most discreet',
			'title'    => 'Ceretone Core One Pro',
			'image'    => 'pick-invisible.svg',
			'score'    => '8.8',
			'best_for' => 'Anyone who wants nothing visible',
			'bullets'  => array(
				'Tiny in-canal design that is nearly invisible',
				'Wind-noise reduction and automatic on/off',
				'Rechargeable with a pocket charging case',
			),
			'featured' => false,
		),
	);
}

/**
 * Hearing aid styles (expandable cards with quantity buttons).
 */
function audira_catalog_styles() {
	return audira_apply_saved_products( 'styles', audira_catalog_styles_defaults() );
}

function audira_catalog_styles_defaults() {
	return array(
		array(
			'asin'     => 'B0XXXXXBTE',
			'url'      => 'https://www.amazon.com/s?k=behind+the+ear+otc+hearing+aids',
			'tag'      => 'BTE · Behind-the-Ear',
			'title'    => 'Behind-the-Ear',
			'image'    => 'type-bte.svg',
			'alt'      => 'Behind-the-ear hearing aid with a clear tube and custom earmold',
			'desc'     => 'A small case rests behind the ear and sends sound through a clear tube to a dome or custom earmold. The most powerful style, and the easiest to handle.',
			'features' => array( 'The most amplification power', 'Largest controls — easy for stiff fingers', 'Long battery life', 'Works with domes or custom earmolds' ),
			'ideal'    => 'anyone who puts power and easy handling first.',
		),
		array(
			'asin'     => 'B0XXXXXRIC',
			'url'      => 'https://www.amazon.com/s?k=receiver+in+canal+otc+hearing+aids',
			'tag'      => 'RIC · Receiver-in-Canal',
			'title'    => 'Receiver-in-Canal',
			'image'    => 'type-ric.svg',
			'alt'      => 'Receiver-in-canal hearing aid with a thin wire and soft dome',
			'desc'     => 'The most popular OTC style. A slim case sits behind the ear, joined by a nearly invisible wire to a tiny speaker inside the ear canal.',
			'features' => array( 'Natural, crisp sound', 'Very discreet to wear', 'Many are rechargeable with Bluetooth', 'Comfortable from morning to night' ),
			'ideal'    => 'everyday conversations, family dinners and anyone who wants discretion without trade-offs.',
		),
		array(
			'asin'     => 'B0XXXXXITE',
			'url'      => 'https://www.amazon.com/s?k=in+the+ear+otc+hearing+aids',
			'tag'      => 'ITE · ITC · In-the-Ear',
			'title'    => 'In-the-Ear',
			'image'    => 'type-ite.svg',
			'alt'      => 'In-the-ear hearing aid shell with a dark faceplate',
			'desc'     => 'One self-contained shell that fills the outer bowl of the ear. Nothing behind the ear, so it plays nicely with glasses and face masks.',
			'features' => array( 'Nothing sits behind the ear', 'Friendly with glasses and masks', 'Easy to insert and remove', 'Simple push-button controls' ),
			'ideal'    => 'people who wear glasses every day and want a one-piece device.',
		),
		array(
			'asin'     => 'B0XXXXXIIC',
			'url'      => 'https://www.amazon.com/s?k=invisible+in+canal+otc+hearing+aids',
			'tag'      => 'IIC · CIC · Invisible',
			'title'    => 'Invisible-in-Canal',
			'image'    => 'type-iic.svg',
			'alt'      => 'Pair of tiny invisible-in-canal hearing aids with removal cords',
			'desc'     => 'Tiny devices that sit deep in the ear canal and are practically invisible from the outside. The choice when appearance matters most.',
			'features' => array( 'Practically invisible', 'Featherlight', 'Less wind noise', 'Uses your ear\'s natural acoustics' ),
			'ideal'    => 'mild to moderate hearing loss when appearance is the top priority.',
		),
	);
}

/**
 * Default "More products we recommend" list (Settings → Audira Affiliate).
 * Format per line: Product name | Amazon link. Link-only lines stay hidden.
 */
function audira_catalog_more_default() {
	return implode(
		"\n",
		array(
			'Sennheiser All-Day Clear Bluetooth Hearing Aids | https://amzn.to/4xKo8cG',
			'Yeasound RIC700 Plus Hearing Aids | https://amzn.to/47jgds3',
			'JVC OTC Hearing Aids for Seniors | https://amzn.to/4xMLoa7',
			'BlaidsX Neuro Rechargeable RIC Hearing Aids | https://amzn.to/4yfIQ5n',
			'METIKO Elclear 64-Channel Hearing Aids | https://amzn.to/4yhwRV6',
			'ELEHEAR Beyond — Champagne Gold | https://amzn.to/4yz7kqF',
			'https://amzn.to/4yYtVMW',
			'https://amzn.to/4yc7e7V',
			'https://amzn.to/4d1QMPd',
		)
	);
}

/**
 * Comparison table rows: label, BTE, RIC, ITE, IIC.
 */
function audira_catalog_compare() {
	return array(
		array( 'How visible', 'Visible behind the ear', 'Discreet', 'Slightly visible', 'Nearly invisible' ),
		array( 'Power', 'Highest', 'High', 'Medium', 'Medium' ),
		array( 'Ease of handling', 'Easiest', 'Easy', 'Easy', 'Needs good dexterity' ),
		array( 'Battery', 'Longest life', 'Often rechargeable', 'Average', 'Shortest' ),
		array( 'Bluetooth streaming', 'Common', 'Very common', 'Some models', 'Rare' ),
		array( 'Choose it if you want', 'Maximum power', 'Everyday discretion', 'One-piece comfort', 'Invisibility' ),
	);
}

/**
 * FAQ — also printed as FAQPage structured data on the front page.
 */
function audira_catalog_faqs() {
	return array(
		array(
			'What is the difference between an OTC hearing aid and a sound amplifier (PSAP)?',
			'OTC hearing aids are FDA-regulated medical devices for adults 18 and older with perceived mild to moderate hearing loss. They shape sound to your hearing and often include a self-fitting test. Personal sound amplification products (PSAPs) simply make everything louder and are meant for people with normal hearing in specific situations, like watching TV. If you have trouble following conversations, start with an OTC hearing aid.',
		),
		array(
			'Do I need a prescription or a hearing test to buy OTC hearing aids?',
			'No. Since October 2022, adults 18+ with perceived mild to moderate hearing loss can buy OTC hearing aids without a prescription, exam or fitting. A hearing test is still a smart first step. See a doctor first if you notice sudden hearing loss, hearing loss in only one ear, ear pain, drainage or dizziness.',
		),
		array(
			'Does Medicare cover hearing aids?',
			'Original Medicare (Parts A and B) generally does not cover hearing aids or exams for fitting them. Many Medicare Advantage (Part C) plans include some hearing benefits, so check your plan documents or call your plan before you buy.',
		),
		array(
			'Can I pay with my HSA or FSA?',
			'In most cases, yes. Hearing aids and hearing aid batteries are generally considered qualified medical expenses. Confirm with your plan administrator and keep your Amazon receipt.',
		),
		array(
			'Do I buy on this website or on Amazon?',
			'On Amazon. When you click a button, we send you to Amazon with the product (and the quantity you chose) ready in your cart. Amazon handles payment, shipping, returns and customer service. We may earn a small commission, at no extra cost to you.',
		),
		array(
			'What if the hearing aids don\'t work for me?',
			'Returns follow the policy shown on each Amazon listing, and many hearing aid makers add their own trial period and warranty. Give your brain two to four weeks to adjust — sounds can feel sharp at first — and check the return window before you buy.',
		),
		array(
			'Rechargeable or disposable batteries — which is better?',
			'Rechargeable models are easier for most older adults: drop them in the case at night, no tiny batteries to change. Disposable batteries are cheaper up front and handy when traveling without a charger.',
		),
	);
}
