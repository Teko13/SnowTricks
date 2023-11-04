<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103145715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY comment_ibfk_1');
        $this->addSql('ALTER TABLE user_file DROP FOREIGN KEY user_file_ibfk_1');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE user_file');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY trick_ibfk_1');
        $this->addSql('DROP INDEX author ON trick');
        $this->addSql('DROP INDEX name ON trick');
        $this->addSql('DROP INDEX group_id ON trick');
        $this->addSql('ALTER TABLE trick ADD author_id INT NOT NULL, ADD group_id_id INT NOT NULL, DROP author, DROP description, DROP group_id');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E2F68B530 FOREIGN KEY (group_id_id) REFERENCES groupes (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EF675F31B ON trick (author_id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E2F68B530 ON trick (group_id_id)');
        $this->addSql('ALTER TABLE trick_file DROP FOREIGN KEY trick_file_ibfk_1');
        $this->addSql('DROP INDEX trick_id ON trick_file');
        $this->addSql('ALTER TABLE trick_file CHANGE trick_id trick_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick_file ADD CONSTRAINT FK_C22C05A0B46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_C22C05A0B46B9EE8 ON trick_file (trick_id_id)');
        $this->addSql('ALTER TABLE user CHANGE valide valide TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, content TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, trick_id INT DEFAULT NULL, INDEX trick_id (trick_id), INDEX author (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_file (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, path VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT comment_ibfk_1 FOREIGN KEY (author) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_file ADD CONSTRAINT user_file_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EF675F31B');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E2F68B530');
        $this->addSql('DROP INDEX IDX_D8F0A91EF675F31B ON trick');
        $this->addSql('DROP INDEX IDX_D8F0A91E2F68B530 ON trick');
        $this->addSql('ALTER TABLE trick ADD author INT NOT NULL, ADD description TEXT DEFAULT NULL, ADD group_id INT NOT NULL, DROP author_id, DROP group_id_id');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT trick_ibfk_1 FOREIGN KEY (author) REFERENCES user (id)');
        $this->addSql('CREATE INDEX author ON trick (author)');
        $this->addSql('CREATE UNIQUE INDEX name ON trick (name)');
        $this->addSql('CREATE INDEX group_id ON trick (group_id)');
        $this->addSql('ALTER TABLE trick_file DROP FOREIGN KEY FK_C22C05A0B46B9EE8');
        $this->addSql('DROP INDEX IDX_C22C05A0B46B9EE8 ON trick_file');
        $this->addSql('ALTER TABLE trick_file CHANGE trick_id_id trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick_file ADD CONSTRAINT trick_file_ibfk_1 FOREIGN KEY (trick_id) REFERENCES trick (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX trick_id ON trick_file (trick_id)');
        $this->addSql('ALTER TABLE user CHANGE valide valide TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
