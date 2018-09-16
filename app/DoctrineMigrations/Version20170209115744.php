<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170209115744 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, twitter_id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, today_since_id VARCHAR(255) NOT NULL, since_id_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649C63E6FFF (twitter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE past_timeline (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, timeline_json LONGTEXT NOT NULL, create_at DATETIME NOT NULL, update_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_10229CD9AA9E377A (date), INDEX IDX_10229CD9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE past_timeline ADD CONSTRAINT FK_10229CD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE past_timeline DROP FOREIGN KEY FK_10229CD9A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE past_timeline');
    }
}
