const React = require('react');
const Navbar = require('react-bootstrap').Navbar;
const Nav = require('react-bootstrap').Nav;
const NavItem = require('react-bootstrap').NavItem;
const Menu = require('./menu.jsx');

const Header = React.createClass({
  get_kaomoji: function() {
    const kaomoji_list = ['＼(・｀(ｪ)・)/', '(*ノ・ω・）', 'o (◡‿◡✿)', 'ヽ(*・ω・)ﾉ'];
    const rand = Math.round(Math.random() * kaomoji_list.length - 1);
    return kaomoji_list[rand];
  },
  render: function() {
      return (
          <Navbar>
              <Nav>
                  <div className="pull-left header-title">
                      <h1>Hello @{this.props.app_user_username} {this.get_kaomoji()}</h1>
                  </div>
                  <div className="pull-right">
                      <Menu onClick={this.props.getDailyJson} timeline_date_list={this.props.timeline_date_list} />
                  </div>
              </Nav>
          </Navbar>
      );
  }
});

module.exports = Header;
