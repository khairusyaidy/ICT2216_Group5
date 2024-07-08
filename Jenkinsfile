pipeline {
    agent any

    stages {
        stage('Checkout SCM') {
            steps {
                git(url: 'https://github.com/muhdmarhakim/ICT2216_Group5.git', branch: 'main')
            }
        }

        stage('OWASP DependencyCheck') {
            steps {
              dependencyCheck(additionalArguments: '--format HTML --format XML', odcInstallation: 'OWASP Dependency-Check Vulnerabilities')
            }
        }

        stage('Composer Install and Test') {
            agent {
                docker { image 'composer:latest' }
            }
            steps {
                sh 'composer install'
                sh './vendor/bin/phpunit --log-junit  logs/unitreport.xml -c tests/phpunit.xml tests'
            }
        }

        post {
            always {
                junit testResults: 'logs/unitreport.xml"
            }
        }
    }
}
