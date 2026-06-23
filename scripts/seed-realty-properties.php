<?php

if ( ! defined( 'ABSPATH' ) ) {
	define( 'WP_USE_THEMES', false );
	require dirname( __DIR__ ) . '/wp-load.php';
}

if ( ! function_exists( 'update_field' ) ) {
	fwrite( STDERR, "ACF is required.\n" );
	exit( 1 );
}

class Realty_Property_Seeder {

	private const TARGET_TOTAL = 100;

	private array $district_slugs = array();

	private array $image_ids = array();

	private array $name_prefixes = array(
		'Meridian',
		'Horizon',
		'Apex',
		'Summit',
		'Crystal',
		'Sterling',
		'Vanguard',
		'Legacy',
		'Paramount',
		'Atlas',
		'Granite',
		'Riverside',
		'Metropolitan',
		'Crown',
		'Liberty',
		'Emerald',
		'Harbor',
		'Centennial',
		'Northgate',
		'Westfield',
		'EastPoint',
		'SouthBridge',
		'Parkview',
		'Lakeview',
		'Skyline',
		'Cornerstone',
		'Pinnacle',
		'Regent',
		'Monarch',
		'Capital',
		'Civic',
		'Union',
		'Founders',
		'Commerce',
		'Enterprise',
		'Executive',
		'Corporate',
		'Global',
		'Premier',
		'Signature',
		'Ambassador',
		'Sovereign',
		'Continental',
		'Atlantic',
		'Central',
		'Gateway',
		'Landmark',
		'Prestige',
		'Vertex',
		'Beacon',
	);

	private array $name_suffixes = array(
		'Business Center',
		'Office Tower',
		'Corporate Plaza',
		'Commercial Building',
		'Executive Suites',
		'Professional Center',
		'Trade Center',
		'Business Park',
		'Office Complex',
		'Commercial Hub',
		'Workplace Tower',
		'Enterprise House',
	);

	private array $subtitle_templates = array(
		'Class A offices with panoramic city views',
		'Modern flexible workspace near public transit',
		'Premium commercial space in a prime business district',
		'Energy-efficient building with smart access control',
		'Renovated offices with open-plan layouts',
		'Corner location with strong foot traffic',
		'Quiet professional environment with on-site parking',
		'High-ceiling units suitable for creative teams',
		'Move-in ready suites with conference facilities',
		'Landmark address for established companies',
		'Mixed-use commercial property with retail frontage',
		'Secure building with 24/7 reception services',
	);

	private array $description_intros = array(
		'This property offers well-proportioned commercial space designed for companies that value visibility, comfort, and long-term operational efficiency.',
		'Located in an active business corridor, the building combines practical floor plans with reliable infrastructure for day-to-day corporate use.',
		'The site presents a strong option for teams seeking a professional address, flexible unit sizes, and convenient access to local services.',
		'Built for modern businesses, the property features efficient layouts, dependable utilities, and a presentation suited to client-facing operations.',
		'An established commercial asset with solid construction, practical amenities, and room to scale as your organization grows.',
	);

	private array $description_features = array(
		'Natural light, efficient HVAC, and structured parking help maintain a productive working environment throughout the year.',
		'Nearby transport links, cafés, and service providers make daily operations straightforward for staff and visitors alike.',
		'The building supports a range of office configurations, from compact teams to multi-department headquarters.',
		'Common areas are maintained to a high standard, reinforcing a polished first impression for partners and clients.',
		'Flexible lease options and varied unit sizes allow businesses to adapt the space to changing headcount and workflow needs.',
	);

	private array $description_outros = array(
		'Ideal for companies looking for a dependable base in a competitive market.',
		'A practical choice for firms prioritizing location, presentation, and operational stability.',
		'Suitable for both growing businesses and established organizations seeking a refined commercial address.',
		'Contact us to schedule a viewing and review available floor plans.',
		'Strong value for businesses that need a professional setting without compromising on accessibility.',
	);

	private array $building_types = array( 'panel', 'brick', 'foam' );

	private array $floor_options = array( '1', '2', '5', '10', '20' );

	private array $yes_no = array( 'yes', 'no' );

	public function run(): void {
		mt_srand( 20260623 );

		$this->load_districts();
		$this->load_images();

		$existing = get_posts(
			array(
				'post_type'      => 'realty',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'fields'         => 'ids',
			)
		);

		$used_titles = array();
		$index       = 0;

		foreach ( $existing as $post_id ) {
			++$index;
			$this->seed_post( $post_id, $index, $used_titles, true );
		}

		$to_create = self::TARGET_TOTAL - count( $existing );

		for ( $i = 0; $i < $to_create; $i++ ) {
			++$index;
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'realty',
					'post_status'  => 'publish',
					'post_title'   => 'Temporary title ' . $index,
					'post_content' => '',
				),
				true
			);

			if ( is_wp_error( $post_id ) ) {
				fwrite( STDERR, 'Failed to create post: ' . $post_id->get_error_message() . PHP_EOL );
				continue;
			}

			$this->seed_post( $post_id, $index, $used_titles, false );
		}

		wp_cache_flush();

		$final_count = wp_count_posts( 'realty' )->publish;
		echo sprintf( "Done. Published realty posts: %d\n", (int) $final_count );
	}

	private function load_districts(): void {
		$terms = get_terms(
			array(
				'taxonomy'   => 'district',
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			fwrite( STDERR, "No district terms found.\n" );
			exit( 1 );
		}

		usort(
			$terms,
			static function ( $a, $b ) {
				return strcmp( $a->slug, $b->slug );
			}
		);

		foreach ( $terms as $term ) {
			$this->district_slugs[] = $term->slug;
		}
	}

	private function load_images(): void {
		$attachments = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_mime_type' => 'image',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		$this->image_ids = ! empty( $attachments ) ? $attachments : array();
	}

	private function pick_district_for_index( int $index ): string {
		$position = ( $index - 1 ) % count( $this->district_slugs );
		return $this->district_slugs[ $position ];
	}

	private function seed_post( int $post_id, int $index, array &$used_titles, bool $keep_district ): void {
		$title = $this->generate_unique_title( $used_titles );
		$slug  = sanitize_title( $title );

		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $this->generate_description( $title ),
			)
		);

		if ( ! $keep_district ) {
			wp_set_object_terms( $post_id, array( $this->pick_district_for_index( $index ) ), 'district' );
		}

		update_field( 'house_title', $this->generate_subtitle(), $post_id );
		update_field( 'house_coordinates', $this->generate_coordinates(), $post_id );
		update_field( 'number_of_floors', $this->random_from( $this->floor_options ), $post_id );
		update_field( 'building_type', $this->random_from( $this->building_types ), $post_id );
		update_field( 'ecological', (string) mt_rand( 1, 5 ), $post_id );

		if ( ! empty( $this->image_ids ) ) {
			update_field( 'house_image', $this->random_from( $this->image_ids ), $post_id );
		}

		update_field( 'place', $this->generate_places(), $post_id );

		echo sprintf( "Updated #%d: %s\n", $post_id, $title );
	}

	private function generate_unique_title( array &$used_titles ): string {
		$attempt = 0;

		do {
			$prefix = $this->random_from( $this->name_prefixes );
			$suffix = $this->random_from( $this->name_suffixes );
			$title  = $prefix . ' ' . $suffix;
			++$attempt;
		} while ( isset( $used_titles[ $title ] ) && $attempt < 50 );

		if ( isset( $used_titles[ $title ] ) ) {
			$title .= ' ' . mt_rand( 100, 999 );
		}

		$used_titles[ $title ] = true;

		return $title;
	}

	private function generate_subtitle(): string {
		return $this->random_from( $this->subtitle_templates );
	}

	private function generate_description( string $title ): string {
		$paragraphs = array(
			$this->random_from( $this->description_intros ),
			$title . ' ' . lcfirst( $this->random_from( $this->description_features ) ),
			$this->random_from( $this->description_outros ),
		);

		return implode( "\n\n", $paragraphs );
	}

	private function generate_coordinates(): string {
		$lat = 46.40 + ( mt_rand( 0, 1000 ) / 10000 );
		$lng = 30.60 + ( mt_rand( 0, 1000 ) / 10000 );

		return sprintf( '%.4f %.4f', $lat, $lng );
	}

	private function generate_places(): array {
		$count  = mt_rand( 1, 4 );
		$places = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$row = array(
				'area'     => (string) mt_rand( 45, 220 ),
				'rooms'    => (string) mt_rand( 2, 8 ),
				'balcony'  => $this->random_from( $this->yes_no ),
				'bathroom' => $this->random_from( $this->yes_no ),
			);

			if ( ! empty( $this->image_ids ) ) {
				$row['place_image'] = $this->random_from( $this->image_ids );
			}

			$places[] = $row;
		}

		return $places;
	}

	private function random_from( array $items ) {
		return $items[ array_rand( $items ) ];
	}
}

( new Realty_Property_Seeder() )->run();
