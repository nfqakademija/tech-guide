<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180410082043 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE question_category');
        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD3512469DE2');
        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD351E27F6BF');
        $this->addSql('ALTER TABLE category_question DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD3512469DE2 FOREIGN KEY (category_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD351E27F6BF FOREIGN KEY (question_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_question ADD PRIMARY KEY (question_id, category_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE question_category (question_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_6544A9CD1E27F6BF (question_id), INDEX IDX_6544A9CD12469DE2 (category_id), PRIMARY KEY(question_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_category ADD CONSTRAINT FK_6544A9CD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_category ADD CONSTRAINT FK_6544A9CD1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD351E27F6BF');
        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD3512469DE2');
        $this->addSql('ALTER TABLE category_question DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD351E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD3512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_question ADD PRIMARY KEY (category_id, question_id)');
    }
}
