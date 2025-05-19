<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250516192152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_log ADD message VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_log ADD CONSTRAINT FK_F9383BB0642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F9383BB0642B8210 ON admin_log (admin_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offer CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE price price INT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE payment CHANGE amount amount INT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE order_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6D28840DA76ED395 ON payment (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_DD19F013C9CBE4C9 ON ticket_order
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_order ADD payment_id INT DEFAULT NULL, DROP qrcode, CHANGE status status VARCHAR(50) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE order_key order_key VARCHAR(255) DEFAULT NULL, CHANGE validated_at validated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_order ADD CONSTRAINT FK_DD19F0134C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_DD19F0134C3A3BB ON ticket_order (payment_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649FBF3A905 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE first_name first_name VARCHAR(100) NOT NULL, CHANGE last_name last_name VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE security_key security_key VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_log DROP FOREIGN KEY FK_F9383BB0642B8210
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F9383BB0642B8210 ON admin_log
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE admin_log DROP message, CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offer CHANGE name name VARCHAR(50) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6D28840DA76ED395 ON payment
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE payment CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE user_id order_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_order DROP FOREIGN KEY FK_DD19F0134C3A3BB
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_DD19F0134C3A3BB ON ticket_order
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ticket_order ADD qrcode VARCHAR(255) NOT NULL, DROP payment_id, CHANGE order_key order_key VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE validated_at validated_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_DD19F013C9CBE4C9 ON ticket_order (order_key)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE security_key security_key VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649FBF3A905 ON user (security_key)
        SQL);
    }
}
