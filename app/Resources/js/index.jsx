import $ from 'jquery'
import autobind from 'autobind-decorator'
import ReactOnRails from 'react-on-rails'
import React from 'react'
import Timeline from './components/timeline.jsx'
import Header from './components/header.jsx'

import '../sass/common/common.scss'
import '../sass/page/index.scss'

@autobind
export default class App extends React.Component {

    constructor(props, context) {
        super(props, context)
        this.state = {
            timelineJson: this.props.timelineJson
        }
    }

    getDailyJson(date) {
        $.get(this.props.jsonDailyUrl + '/' + date, ((json)=> {
            this.setState({timelineJson: json})
        }).bind(this))

        return 0
    }

    render() {
        return (
            <div>
                <Header
                    getDailyJson={this.getDailyJson.bind(this)}
                    timelineDateList={this.props.timelineDateList}
                    appUsername={this.props.appUsername}
                />
                <Timeline timelineJson={this.state.timelineJson}/>
            </div>
        )
    }
}

ReactOnRails.register({App})
