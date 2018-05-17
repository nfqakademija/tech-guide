<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180517103120 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE filter_usage_html');
        $this->addSql('ALTER TABLE filter_usage ADD html_id INT NOT NULL, CHANGE answer_history_id answer_history_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE filter_usage ADD CONSTRAINT FK_F95AAD8F3CD4754E FOREIGN KEY (html_id) REFERENCES html (id)');
        $this->addSql('CREATE INDEX IDX_F95AAD8F3CD4754E ON filter_usage (html_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE filter_usage_html (filter_usage_id INT NOT NULL, html_id INT NOT NULL, INDEX IDX_6DBE9FF71C3FE33A (filter_usage_id), INDEX IDX_6DBE9FF73CD4754E (html_id), PRIMARY KEY(filter_usage_id, html_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filter_usage_html ADD CONSTRAINT FK_6DBE9FF71C3FE33A FOREIGN KEY (filter_usage_id) REFERENCES filter_usage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_usage_html ADD CONSTRAINT FK_6DBE9FF73CD4754E FOREIGN KEY (html_id) REFERENCES html (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_usage DROP FOREIGN KEY FK_F95AAD8F3CD4754E');
        $this->addSql('DROP INDEX IDX_F95AAD8F3CD4754E ON filter_usage');
        $this->addSql('ALTER TABLE filter_usage DROP html_id, CHANGE answer_history_id answer_history_id INT NOT NULL');
    }
}
