/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

'use strict';

function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(self, call) { if (call && (typeof call === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }
function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }
function _inheritsLoose(subClass, superClass) { subClass.prototype = Object.create(superClass.prototype); subClass.prototype.constructor = subClass; _setPrototypeOf(subClass, superClass); }
function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }
import { Controller } from '@hotwired/stimulus';
var _default = /*#__PURE__*/function (_Controller) {
  function _default() {
    return _callSuper(this, _default, arguments);
  }
  _inheritsLoose(_default, _Controller);
  var _proto = _default.prototype;
  _proto.connect = function connect() {
    var element = this.element;
    var href = element.getAttribute('href');
    if (!href) {
      return;
    }
    var url = new URL(href, 'http://example.com/');
    if (!url) {
      return;
    }
    var expiration = parseInt(url.searchParams.get('expiration'));
    var secondsToExpiration = 1800;
    if (expiration) {
      var now = new Date().getTime() / 1000;
      secondsToExpiration = expiration - now;
    }
    if (secondsToExpiration < 0) {
      secondsToExpiration = 1;
    }
    setTimeout(function () {
      element.classList.add('disabled');
      element.setAttribute('disabled', 'disabled');
      element.setAttribute('tabindex', '-1');
      element.setAttribute('aria-disabled', 'true');
      element.setAttribute('title', 'This link has expired. Please refresh the page, and try again.');
      element.href = '#';
    }, secondsToExpiration * 1000);
  };
  return _default;
}(Controller);
export { _default as default };