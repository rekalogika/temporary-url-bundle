'use strict'

import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    connect() {
        let element = this.element

        let href = element.getAttribute('href')
        if (!href) {
            return;
        }

        let url = new URL(href, 'http://example.com/')
        if (!url) {
          return;
        }

        let expiration = parseInt(url.searchParams.get('expiration'))
        let secondsToExpiration = 1800

        if (expiration) {
            let now = new Date().getTime() / 1000
            secondsToExpiration = expiration - now
        }

        if (secondsToExpiration < 0) {
            secondsToExpiration = 1
        }

        setTimeout(function () {
            element.classList.add('disabled')
            element.setAttribute('disabled', 'disabled')
            element.setAttribute('tabindex', '-1')
            element.setAttribute('aria-disabled', 'true')
            element.setAttribute('title', 'This link has expired. Please refresh the page, and try again.')
            element.href = '#'
        }, secondsToExpiration * 1000)
    }
}