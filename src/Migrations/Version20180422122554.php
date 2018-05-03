<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180422122554 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE influence_area (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'content VARCHAR(100) NOT NULL, ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE influence_area_question (' .
            'influence_area_id INT NOT NULL, ' .
            'question_id INT NOT NULL, ' .
            'INDEX IDX_13973352915ABAF6 (influence_area_id), ' .
            'INDEX IDX_139733521E27F6BF (question_id), ' .
            'PRIMARY KEY(influence_area_id, question_id)) ' .
            'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE influence_area_question ADD CONSTRAINT FK_13973352915ABAF6 ' .
            'FOREIGN KEY (influence_area_id) REFERENCES influence_area (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE influence_area_question ' .
            'ADD CONSTRAINT FK_139733521E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE influence_area_question DROP FOREIGN KEY FK_13973352915ABAF6');
        $this->addSql('DROP TABLE influence_area');
        $this->addSql('DROP TABLE influence_area_question');
    }
}
