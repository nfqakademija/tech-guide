<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180511102734 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE filter_shop DROP FOREIGN KEY FK_C4502BDBD395B25E');
        $this->addSql('ALTER TABLE regex DROP FOREIGN KEY FK_4204F8CAD395B25E');
        $this->addSql('DROP TABLE filter');
        $this->addSql('DROP TABLE filter_shop');
        $this->addSql('DROP INDEX IDX_4204F8CAD395B25E ON regex');
        $this->addSql('ALTER TABLE regex ADD shop_id INT NOT NULL, CHANGE filter_id influence_area_id INT NOT NULL');
        $this->addSql('ALTER TABLE regex ' .
            'ADD CONSTRAINT FK_4204F8CA915ABAF6 FOREIGN KEY (influence_area_id) REFERENCES influence_area (id)');
        $this->addSql('ALTER TABLE regex ' .
            'ADD CONSTRAINT FK_4204F8CA4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE INDEX IDX_4204F8CA915ABAF6 ON regex (influence_area_id)');
        $this->addSql('CREATE INDEX IDX_4204F8CA4D16C4DD ON regex (shop_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE filter (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'influence_area_id INT NOT NULL, ' .
            'INDEX IDX_7FC45F1D915ABAF6 (influence_area_id), ' .
            'PRIMARY KEY(id)) ' .
            'DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_shop (' .
            'filter_id INT NOT NULL, ' .
            'shop_id INT NOT NULL, ' .
            'INDEX IDX_C4502BDBD395B25E (filter_id), ' .
            'INDEX IDX_C4502BDB4D16C4DD (shop_id), ' .
            'PRIMARY KEY(filter_id, shop_id)) ' .
            'DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filter ' .
            'ADD CONSTRAINT FK_7FC45F1D915ABAF6 FOREIGN KEY (influence_area_id) REFERENCES influence_area (id)');
        $this->addSql('ALTER TABLE filter_shop ' .
            'ADD CONSTRAINT FK_C4502BDB4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_shop ' .
            'ADD CONSTRAINT FK_C4502BDBD395B25E FOREIGN KEY (filter_id) REFERENCES filter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE regex DROP FOREIGN KEY FK_4204F8CA915ABAF6');
        $this->addSql('ALTER TABLE regex DROP FOREIGN KEY FK_4204F8CA4D16C4DD');
        $this->addSql('DROP INDEX IDX_4204F8CA915ABAF6 ON regex');
        $this->addSql('DROP INDEX IDX_4204F8CA4D16C4DD ON regex');
        $this->addSql('ALTER TABLE regex ADD filter_id INT NOT NULL, DROP influence_area_id, DROP shop_id');
        $this->addSql('ALTER TABLE regex ' .
            'ADD CONSTRAINT FK_4204F8CAD395B25E FOREIGN KEY (filter_id) REFERENCES filter (id)');
        $this->addSql('CREATE INDEX IDX_4204F8CAD395B25E ON regex (filter_id)');
    }
}
