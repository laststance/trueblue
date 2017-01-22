import $ from 'jquery'
import autobind from 'autobind-decorator'
import ReactOnRails from 'react-on-rails'
import React from 'react'
import Timeline from './components/timeline.jsx'
import Header from './components/header.jsx'

import '../sass/common.scss'
import '../sass/index.scss'

@autobind
export default class App extends React.Component {

    constructor(props, context) {
        super(props, context)
        this.state = {
            todayTimeline:    this.props.timelineJson,
            timelineJson:     this.props.timelineJson,
            jsonDailyUrl:     this.props.jsonDailyUrl,
            timelineDateList: this.props.timelineDateList,
            appUsername:      this.props.appUsername
        }
    }

    getDailyJson(date) {
        const newDate = new Date()
        const today = newDate.getFullYear() + '-' + (newDate.getMonth() + 1) + '-' + ('0' + newDate.getDate()).slice(-2)

        if (date === today) {
            this.setState({timelineJson: this.state.todayTimeline})
        } else {
            $.get(this.state.jsonDailyUrl + '/' + date, ((json)=> {
                this.setState({timelineJson: json})
            }).bind(this))
        }
        return 0
    }

    render() {
        return (
            <div>
                <Header
                    getDailyJson={this.getDailyJson.bind(this)}
                    timelineDateList={this.state.timelineDateList}
                    appUsername={this.state.appUsername}
                />
                <Timeline timelineJson={this.state.timelineJson}/>
            </div>
        )
    }
}

ReactOnRails.register({App})
