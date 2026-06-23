# Realty Etcetera

Corporate WordPress site for presenting and browsing commercial real estate: offices, business centers, and properties for corporate clients.

## Stack

| Component | Version / Solution |
|-----------|-------------------|
| WordPress | 7.0 |
| PHP | 8.2 (OSPanel) |
| MySQL | 8.4 |
| Theme | Understrap Child 1.2.0 (Bootstrap 5) |
| ACF Pro | Real estate object fields |
| Custom plugin | [realty-plugin](wp-content/plugins/realty-plugin) |

## Local setup (OSPanel)

1. Project path: `d:\OSPanel\home\realtyetcetera.local`
2. OSPanel config: `.osp/project.ini` - domain `realtyetcetera.local`
3. Database: `realty_etcetera_db`, user `root`, empty password
4. Site URL: http://realtyetcetera.local

### Theme styles and scripts build

```powershell
cd wp-content/themes/understrap-child-1.2.0
npm install
npm run dist
```

## Project structure

```
realtyetcetera.local/
├── .osp/                          # OSPanel settings
├── wp-content/
│   ├── plugins/realty-plugin/     # CPT realty, taxonomy district
│   └── themes/understrap-child-1.2.0/
│       ├── page-templates/realtypage.php   # Catalog with AJAX filters
│       ├── loop-templates/content-ajax.php # Listing card
│       ├── functions.php          # Pagination, ajaxUrl for catalog
│       └── src/sass/theme/        # Custom styles
├── wp-config.php
└── realty_etcetera_db.sql         # Database dump
```

## Core functionality

- **CPT `realty`** - real estate listings (plugin)
- **Taxonomy `district`** - districts / categories
- **ACF fields** - `house_title`, `house_image`, `building_type`, `number_of_floors`, `ecological`, repeater `place`
- **Realty Page template** - catalog page with filters
- **AJAX** - filtering and sorting without page reload (plugin `realty-plugin`, action `ajax_handler`)

### Catalog filters

| Parameter | Form field | Description |
|-----------|------------|-------------|
| Alphabet | `search_alph` | First letter of the title |
| Eco rating | `search_raiting` | ACF `ecological` value (1-5) |
| Sort order | `search_raiting_order` | ASC / DESC by eco rating |
| District | `blog_category[]` | `district` taxonomy |
| Building type | `type_build` | panel / brick / foam |

## Author

Andrew Veliksar - https://github.com/Veliksar
