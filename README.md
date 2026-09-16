# Loja Virtual Completa - Cliente X

Este repositório contém o código customizado do Tema Filho (Child Theme) baseado no Hello Elementor para a loja virtual do Cliente X.

## Tecnologias Utilizadas
- WordPress
- WooCommerce (Base da Loja)
- Hello Elementor (Tema Pai)
- Ambiente: Ubuntu Linux (Apache/MySQL)

## Como instalar em desenvolvimento
1. Clone este repositório dentro da pasta `wp-content/themes/` de uma instalação WordPress limpa.
2. Ative o tema "Hello Elementor Child" no painel administrativo.
3. Instale e configure os plugins WooCommerce e gateways de pagamento (necessário configurar manualmente as chaves de API).

## Segurança (Confiança Zero)
Este repositório NÃO contém:
- Credenciais de banco de dados (`wp-config.php`).
- Chaves de API de produção dos gateways de pagamento.
- Arquivos de mídia (uploads).

## Dependências do Sistema

Para o funcionamento correto desta loja virtual, é obrigatória a instalação e ativação dos seguintes plugins no WordPress:

1.  **WooCommerce** (Base da loja, carrinho e gestão de pedidos).

...
## Status da Implantação

- [x] Ambiente Ubuntu (Apache/MySQL) configurado.
- [x] WordPress instalado.
- [x] Repositório Git isolado no Tema Filho.
- [x] WooCommerce instalado, ativado e configuração básica realizada (endereço, moeda).