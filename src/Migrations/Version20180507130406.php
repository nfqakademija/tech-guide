<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180507130406 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE regex (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'filter_id INT NOT NULL, ' .
            'html_reducing_regex VARCHAR(255) DEFAULT NULL, ' .
            'content_regex VARCHAR(255) NOT NULL, ' .
            'INDEX IDX_4204F8CAD395B25E (filter_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regex_category (' .
            'regex_id INT NOT NULL, ' .
            'category_id INT NOT NULL, ' .
            'INDEX IDX_9F3455FC21717948 (regex_id), ' .
            'INDEX IDX_9F3455FC12469DE2 (category_id), ' .
            'PRIMARY KEY(regex_id, category_id)) ' .
            'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'influence_area_id INT NOT NULL, ' .
            'url_parameter VARCHAR(100) NOT NULL, ' .
            'INDEX IDX_7FC45F1D915ABAF6 (influence_area_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_shop (' .
            'filter_id INT NOT NULL, ' .
            'shop_id INT NOT NULL, ' .
            'INDEX IDX_C4502BDBD395B25E (filter_id), ' .
            'INDEX IDX_C4502BDB4D16C4DD (shop_id), ' .
            'PRIMARY KEY(filter_id, shop_id)) ' .
            'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE regex ' .
            'ADD CONSTRAINT FK_4204F8CAD395B25E FOREIGN KEY (filter_id) REFERENCES filter (id)');
        $this->addSql('ALTER TABLE regex_category ' .
            'ADD CONSTRAINT FK_9F3455FC21717948 FOREIGN KEY (regex_id) REFERENCES regex (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regex_category ' .
            'ADD CONSTRAINT FK_9F3455FC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter ' .
            'ADD CONSTRAINT FK_7FC45F1D915ABAF6 FOREIGN KEY (influence_area_id) REFERENCES influence_area (id)');
        $this->addSql('ALTER TABLE filter_shop ' .
            'ADD CONSTRAINT FK_C4502BDBD395B25E FOREIGN KEY (filter_id) REFERENCES filter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_shop ' .
            'ADD CONSTRAINT FK_C4502BDB4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop ' .
            'ADD filter_value_separator VARCHAR(5) NOT NULL, ' .
            'ADD first_filter_value_separator VARCHAR(5) DEFAULT NULL, ' .
            'ADD first_filter_separator VARCHAR(5) DEFAULT NULL, ' .
            'ADD filter_separator VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE shop_category ' .
            'DROP color_filter, DROP price_filter, DROP memory_filter, ' .
            'DROP ssd_filter, DROP hdd_filter, DROP processor_filter, ' .
            'DROP ram_filter, DROP size_filter, DROP resolution_filter');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE regex_category DROP FOREIGN KEY FK_9F3455FC21717948');
        $this->addSql('ALTER TABLE regex DROP FOREIGN KEY FK_4204F8CAD395B25E');
        $this->addSql('ALTER TABLE filter_shop DROP FOREIGN KEY FK_C4502BDBD395B25E');
        $this->addSql('DROP TABLE regex');
        $this->addSql('DROP TABLE regex_category');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE filter_shop');
        $this->addSql('ALTER TABLE shop ' .
            'DROP filter_value_separator, DROP first_filter_value_separator, ' .
            'DROP first_filter_separator, DROP filter_separator');
        $this->addSql('ALTER TABLE shop_category ' .
            'ADD color_filter VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD price_filter VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD memory_filter VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD ssd_filter VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD hdd_filter VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD processor_filter VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD ram_filter VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD size_filter VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD resolution_filter VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
