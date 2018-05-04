<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180408184838 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE category (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'category_name VARCHAR(100) NOT NULL, ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_question (' .
            'category_id INT NOT NULL, ' .
            'question_id INT NOT NULL, ' .
            'INDEX IDX_18DCD3512469DE2 (category_id), ' .
            'INDEX IDX_18DCD351E27F6BF (question_id), ' .
            'PRIMARY KEY(category_id, question_id)) ' .
            'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'homepage VARCHAR(100) NOT NULL, ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_category (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'shop_id INT NOT NULL, ' .
            'category_id INT NOT NULL, ' .
            'search_filter VARCHAR(100) DEFAULT NULL, ' .
            'category_filter VARCHAR(100) DEFAULT NULL, ' .
            'prefix VARCHAR(100) DEFAULT NULL, ' .
            'INDEX IDX_DDF4E3574D16C4DD (shop_id), ' .
            'INDEX IDX_DDF4E35712469DE2 (category_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'follow_up_question_id INT DEFAULT NULL, ' .
            'priority INT NOT NULL, value VARCHAR(100) NOT NULL, ' .
            'UNIQUE INDEX UNIQ_B6F7494E556238C3 (follow_up_question_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_category (' .
            'question_id INT NOT NULL, ' .
            'category_id INT NOT NULL, ' .
            'INDEX IDX_6544A9CD1E27F6BF (question_id), ' .
            'INDEX IDX_6544A9CD12469DE2 (category_id), ' .
            'PRIMARY KEY(question_id, category_id)) ' .
            'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'question_id INT NOT NULL, ' .
            'value VARCHAR(100) NOT NULL, ' .
            'INDEX IDX_DADD4A251E27F6BF (question_id), ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guidebot_sentence (' .
            'id INT AUTO_INCREMENT NOT NULL, ' .
            'sentence VARCHAR(100) NOT NULL, ' .
            'priority INT NOT NULL, ' .
            'purpose VARCHAR(100) NOT NULL, ' .
            'PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_question ' .
            'ADD CONSTRAINT FK_18DCD3512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_question ' .
            'ADD CONSTRAINT FK_18DCD351E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_category ' .
            'ADD CONSTRAINT FK_DDF4E3574D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_category ' .
            'ADD CONSTRAINT FK_DDF4E35712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE question ' .
            'ADD CONSTRAINT FK_B6F7494E556238C3 FOREIGN KEY (follow_up_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_category ' .
            'ADD CONSTRAINT FK_6544A9CD1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_category ' .
            'ADD CONSTRAINT FK_6544A9CD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer ' .
            'ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD3512469DE2');
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E35712469DE2');
        $this->addSql('ALTER TABLE question_category DROP FOREIGN KEY FK_6544A9CD12469DE2');
        $this->addSql('ALTER TABLE shop_category DROP FOREIGN KEY FK_DDF4E3574D16C4DD');
        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD351E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E556238C3');
        $this->addSql('ALTER TABLE question_category DROP FOREIGN KEY FK_6544A9CD1E27F6BF');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_question');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE shop_category');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_category');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE guidebot_sentence');
    }
}
