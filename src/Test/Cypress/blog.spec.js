describe('Blog', function () {

    it('Load home page', function () {
        cy.visit('/blog');

        // we should be redirected to /dashboard
        cy.url().should('include', '/blog');

        cy.contains('Hello world!');
    });

    it('View first category', function () {
        cy.visit('/blog');

        cy.contains('Quia corrupti quaerat et mollitia').click();

        // we should be redirected to /dashboard
        cy.url().should('include', '/quia-corrupti-quaerat-et-mollitia');
    });
})