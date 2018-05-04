<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429193623 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE answer_history (' .
            'id INT AUTO_INCREMENT NOT NULL, '.
            'category_id INT NOT NULL, ' .
            'added_at DATETIME NOT NULL, ' .
            'INDEX IDX_6FFABF3A12469DE2 (category_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_history_answer (' .
            'answer_history_id INT NOT NULL, ' .
            'answer_id INT NOT NULL, ' .
            'INDEX IDX_A5A059B371880C6C (answer_history_id), ' .
            'INDEX IDX_A5A059B3AA334807 (answer_id), ' .
            'PRIMARY KEY(answer_history_id, answer_id)) ' .
            'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer_history ' .
            'ADD CONSTRAINT FK_6FFABF3A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE answer_history_answer ' .
            'ADD CONSTRAINT FK_A5A059B371880C6C ' .
            'FOREIGN KEY (answer_history_id) REFERENCES answer_history (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer_history_answer ' .
            'ADD CONSTRAINT FK_A5A059B3AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE answer_history_answer DROP FOREIGN KEY FK_A5A059B371880C6C');
        $this->addSql('DROP TABLE answer_history');
        $this->addSql('DROP TABLE answer_history_answer');
    }
}
