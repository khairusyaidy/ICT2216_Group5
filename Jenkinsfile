pipeline {
  agent any
  stages {
    stage('Build') {
      steps {
        echo 'Building...'
      }
    }

    stage('OWASP Dependency-Check Vulnerabilities') {
      steps {
        script {
          dependencyCheck additionalArguments: '''
-o './'
-s './'
-f 'ALL'
--prettyPrint''', odcInstallation: 'OWASP Dependency-Check Vulnerabilities'

          // Publish the report
          dependencyCheckPublisher pattern: 'dependency-check-report.xml'
        }

      }
    }

    stage('Deploy') {
      steps {
        echo 'Deploying...'
      }
    }

  }
  post {
    always {
      echo 'This will always run regardless of the build status.'
    }

    success {
      echo 'Build was successful!'
    }

    failure {
      echo 'Build failed!'
    }

  }
}