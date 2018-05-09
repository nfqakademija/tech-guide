<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180509181957 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shop_question');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shop_question (question_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_6D4FB3D81E27F6BF (question_id), INDEX IDX_6D4FB3D812469DE2 (category_id), PRIMARY KEY(question_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_question ADD CONSTRAINT FK_6D4FB3D812469DE2 FOREIGN KEY (category_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE shop_question ADD CONSTRAINT FK_6D4FB3D81E27F6BF FOREIGN KEY (question_id) REFERENCES shop (id)');
    }
}
