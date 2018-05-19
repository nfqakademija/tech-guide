<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180515201722 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE filter_usage (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'answer_history_id INT NOT NULL, ' .
            'value INT NOT NULL, INDEX IDX_F95AAD8F71880C6C (answer_history_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filter_usage_html (' .
            'filter_usage_id INT NOT NULL, html_id INT NOT NULL, ' .
            'INDEX IDX_6DBE9FF71C3FE33A (filter_usage_id), ' .
            'INDEX IDX_6DBE9FF73CD4754E (html_id), ' .
            'PRIMARY KEY(filter_usage_id, html_id)) ' .
            'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE html (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'shop_id INT NOT NULL, ' .
            'content LONGTEXT NOT NULL, ' .
            'url LONGTEXT NOT NULL, ' .
            'added_at DATETIME NOT NULL, ' .
            'INDEX IDX_1879F8E54D16C4DD (shop_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filter_usage ' .
            'ADD CONSTRAINT FK_F95AAD8F71880C6C FOREIGN KEY (answer_history_id) ' .
            'REFERENCES answer_history (id)');
        $this->addSql('ALTER TABLE filter_usage_html ' .
            'ADD CONSTRAINT FK_6DBE9FF71C3FE33A FOREIGN KEY (filter_usage_id) ' .
            'REFERENCES filter_usage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_usage_html ' .
            'ADD CONSTRAINT FK_6DBE9FF73CD4754E FOREIGN KEY (html_id) ' .
            'REFERENCES html (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE html ' .
            'ADD CONSTRAINT FK_1879F8E54D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_category DROP html, DROP html_added_at');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE filter_usage_html DROP FOREIGN KEY FK_6DBE9FF71C3FE33A');
        $this->addSql('ALTER TABLE filter_usage_html DROP FOREIGN KEY FK_6DBE9FF73CD4754E');
        $this->addSql('DROP TABLE filter_usage');
        $this->addSql('DROP TABLE filter_usage_html');
        $this->addSql('DROP TABLE html');
        $this->addSql('ALTER TABLE shop_category ' .
            'ADD html LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ' .
            'ADD html_added_at DATETIME DEFAULT NULL');
    }
}
